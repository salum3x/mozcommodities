<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CartItem;
use App\Models\Product;
use App\Services\Payment\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    protected PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Get user orders
     */
    public function index(Request $request)
    {
        $orders = Order::with(['items.product'])
            ->where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => collect($orders->items())->map(function ($order) {
                return $this->formatOrder($order);
            }),
            'meta' => [
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
                'per_page' => $orders->perPage(),
                'total' => $orders->total(),
            ]
        ]);
    }

    /**
     * Get single order
     */
    public function show(Request $request, $id)
    {
        $order = Order::with(['items.product'])
            ->where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Pedido não encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $this->formatOrder($order, true)
        ]);
    }

    /**
     * Create new order from cart
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email'],
            'customer_phone' => ['required', 'string', 'max:20'],
            'customer_address' => ['nullable', 'string', 'max:500'],
            'payment_method' => ['required', 'in:mpesa,emola,card,bank_transfer'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erro de validação',
                'errors' => $validator->errors()
            ], 422);
        }

        // Get cart items
        $cartItems = CartItem::with('product')
            ->where('user_id', $request->user()->id)
            ->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Carrinho vazio'
            ], 422);
        }

        // Validate stock availability
        foreach ($cartItems as $item) {
            if ($item->quantity > $item->product->stock_quantity) {
                return response()->json([
                    'success' => false,
                    'message' => "Stock insuficiente para {$item->product->name}"
                ], 422);
            }
        }

        try {
            DB::beginTransaction();

            // Calculate total
            $total = $cartItems->sum(function ($item) {
                return $item->quantity * $item->price_per_kg;
            });

            // Create order
            $order = Order::create([
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'user_id' => $request->user()->id,
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'customer_address' => $request->customer_address,
                'subtotal' => $total,
                'total' => $total,
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'status' => 'pending',
                'notes' => $request->notes,
            ]);

            // Create order items and update stock
            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'product_name' => $cartItem->product->name,
                    'price' => $cartItem->price_per_kg,
                    'quantity' => $cartItem->quantity,
                    'subtotal' => $cartItem->quantity * $cartItem->price_per_kg,
                ]);

                // Update stock
                $cartItem->product->decrement('stock_quantity', $cartItem->quantity);
            }

            // Clear cart
            CartItem::where('user_id', $request->user()->id)->delete();

            DB::commit();

            // Process payment
            $paymentResult = null;
            if ($request->payment_method !== 'bank_transfer') {
                $paymentResult = $this->paymentService->processPayment($order, $request->payment_method, [
                    'phone' => $request->customer_phone,
                ]);

                if ($paymentResult['success']) {
                    if (isset($paymentResult['transaction_id'])) {
                        $order->update(['transaction_id' => $paymentResult['transaction_id']]);
                    }
                    if (isset($paymentResult['client_secret'])) {
                        $order->update(['payment_reference' => $paymentResult['payment_intent_id']]);
                    }
                }
            }

            $order->load('items.product');

            $response = [
                'success' => true,
                'message' => 'Pedido criado com sucesso',
                'data' => $this->formatOrder($order, true),
            ];

            // Add payment info
            if ($paymentResult) {
                $response['payment'] = [
                    'status' => $paymentResult['success'] ? 'initiated' : 'failed',
                    'message' => $paymentResult['message'] ?? null,
                ];

                if ($request->payment_method === 'card' && isset($paymentResult['client_secret'])) {
                    $response['payment']['client_secret'] = $paymentResult['client_secret'];
                    $response['payment']['stripe_publishable_key'] = config('services.stripe.key');
                }
            }

            return response()->json($response, 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar pedido: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Confirm Stripe payment
     */
    public function confirmStripePayment(Request $request, $orderId)
    {
        $order = Order::where('id', $orderId)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Pedido não encontrado'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'payment_intent_id' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erro de validação',
                'errors' => $validator->errors()
            ], 422);
        }

        $this->paymentService->markAsPaid($order, $request->payment_intent_id);

        return response()->json([
            'success' => true,
            'message' => 'Pagamento confirmado',
            'data' => $this->formatOrder($order->fresh(), true)
        ]);
    }

    /**
     * Cancel order
     */
    public function cancel(Request $request, $id)
    {
        $order = Order::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Pedido não encontrado'
            ], 404);
        }

        if (!in_array($order->status, ['pending'])) {
            return response()->json([
                'success' => false,
                'message' => 'Este pedido não pode ser cancelado'
            ], 422);
        }

        // Restore stock
        foreach ($order->items as $item) {
            if ($item->product) {
                $item->product->increment('stock_quantity', $item->quantity);
            }
        }

        $order->update([
            'status' => 'cancelled',
            'payment_status' => 'cancelled',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pedido cancelado',
            'data' => $this->formatOrder($order, true)
        ]);
    }

    /**
     * Format order for response
     */
    protected function formatOrder(Order $order, bool $full = false): array
    {
        $data = [
            'id' => $order->id,
            'order_number' => $order->order_number,
            'status' => $order->status,
            'status_label' => $this->getStatusLabel($order->status),
            'payment_status' => $order->payment_status,
            'payment_status_label' => $this->getPaymentStatusLabel($order->payment_status),
            'payment_method' => $order->payment_method,
            'payment_method_label' => $this->getPaymentMethodLabel($order->payment_method),
            'total' => (float) $order->total,
            'total_formatted' => number_format($order->total, 2, ',', '.') . ' MZN',
            'items_count' => $order->items->count(),
            'created_at' => $order->created_at->toISOString(),
            'created_at_formatted' => $order->created_at->format('d/m/Y H:i'),
        ];

        if ($full) {
            $data['customer_name'] = $order->customer_name;
            $data['customer_email'] = $order->customer_email;
            $data['customer_phone'] = $order->customer_phone;
            $data['customer_address'] = $order->customer_address;
            $data['subtotal'] = (float) $order->subtotal;
            $data['notes'] = $order->notes;
            $data['paid_at'] = $order->paid_at?->toISOString();
            $data['items'] = $order->items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product_name,
                    'quantity' => (float) $item->quantity,
                    'price' => (float) $item->price,
                    'subtotal' => (float) $item->subtotal,
                    'product' => $item->product ? [
                        'id' => $item->product->id,
                        'slug' => $item->product->slug,
                        'image' => $item->product->image ? asset('storage/' . $item->product->image) : null,
                    ] : null,
                ];
            });
        }

        return $data;
    }

    protected function getStatusLabel(string $status): string
    {
        return match($status) {
            'pending' => 'Pendente',
            'confirmed' => 'Confirmado',
            'processing' => 'Em Processamento',
            'shipped' => 'Enviado',
            'delivered' => 'Entregue',
            'cancelled' => 'Cancelado',
            default => $status,
        };
    }

    protected function getPaymentStatusLabel(string $status): string
    {
        return match($status) {
            'pending' => 'Aguardando Pagamento',
            'paid' => 'Pago',
            'failed' => 'Falhou',
            'refunded' => 'Reembolsado',
            'cancelled' => 'Cancelado',
            default => $status,
        };
    }

    protected function getPaymentMethodLabel(string $method): string
    {
        return match($method) {
            'mpesa' => 'M-Pesa',
            'emola' => 'e-Mola',
            'card' => 'Cartão',
            'bank_transfer' => 'Transferência Bancária',
            default => $method,
        };
    }
}
