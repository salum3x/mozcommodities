<?php

namespace App\Livewire\Public;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CartItem;
use App\Services\Payment\PaymentService;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithFileUploads;

class CheckoutCart extends Component
{
    use WithFileUploads;

    public $cartItems = [];
    public $total = 0;

    // Customer info
    public $customer_name = '';
    public $customer_email = '';
    public $customer_phone = '';
    public $customer_address = '';

    // Payment
    public $payment_method = 'emola';
    public $payment_proof;
    public $notes = '';

    // Payment processing
    public $processing = false;
    public $paymentError = '';
    public $paymentMessage = '';
    public $stripeClientSecret = '';
    public $availableGateways = [];

    // Popup state
    public bool $popupOpen = false;
    public string $popupState = 'idle'; // idle | awaiting | success | failed
    public string $popupTitle = '';
    public string $popupBody = '';
    public int $pollCount = 0;
    #[Locked]
    public ?int $pendingOrderId = null;

    protected PaymentService $paymentService;

    public function boot(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function mount()
    {
        $this->cartItems = CartItem::getCartItems();

        if ($this->cartItems->isEmpty()) {
            return redirect()->route('cart');
        }

        $this->total = CartItem::getCartTotal();
        $this->availableGateways = app(PaymentService::class)->getAvailableGateways();

        // Pre-fill if authenticated
        if (auth()->check()) {
            $this->customer_name = auth()->user()->name;
            $this->customer_email = auth()->user()->email;
            $this->customer_phone = auth()->user()->phone ?? '';
            $this->customer_address = auth()->user()->address ?? '';
        }
    }

    public function updatedPaymentMethod()
    {
        $this->paymentError = '';
        $this->paymentMessage = '';
        $this->stripeClientSecret = '';
    }

    public function placeOrder()
    {
        $this->paymentError = '';
        $this->paymentMessage = '';
        $this->processing = true;

        $rules = [
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email',
            'customer_phone' => 'required|string|max:20',
            'payment_method' => 'required|in:emola,bank_transfer',
        ];

        // Require payment proof for bank transfer
        if ($this->payment_method === 'bank_transfer') {
            $rules['payment_proof'] = 'required|image|max:2048';
        }

        $this->validate($rules);

        try {
            $order = DB::transaction(function () {
                $orderNumber = 'ORD-' . strtoupper(bin2hex(random_bytes(8)));

                $order = Order::create([
                    'order_number' => $orderNumber,
                    'user_id' => auth()->id(),
                    'customer_name' => $this->customer_name,
                    'customer_email' => $this->customer_email,
                    'customer_phone' => $this->customer_phone,
                    'customer_address' => $this->customer_address,
                    'subtotal' => $this->total,
                    'total' => $this->total,
                    'payment_method' => $this->payment_method,
                    'payment_status' => 'pending',
                    'status' => 'pending',
                    'notes' => $this->notes,
                ]);

                foreach ($this->cartItems as $cartItem) {
                    $product = \App\Models\Product::lockForUpdate()->find($cartItem->product_id);
                    if (!$product || !$product->is_active || $product->approval_status !== 'approved') {
                        throw new \RuntimeException("Produto {$cartItem->product->name} indisponível.");
                    }
                    if ($cartItem->quantity > $product->stock_quantity) {
                        throw new \RuntimeException("Stock insuficiente para {$product->name}. Disponível: {$product->stock_quantity} {$product->unit}");
                    }

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $cartItem->product_id,
                        'product_name' => $product->name,
                        'price' => $cartItem->price_per_kg,
                        'quantity' => $cartItem->quantity,
                        'subtotal' => $cartItem->quantity * $cartItem->price_per_kg,
                    ]);
                }

                return $order;
            });

            // Process payment based on method
            if ($this->payment_method === 'bank_transfer') {
                // Handle bank transfer with proof upload
                if ($this->payment_proof) {
                    $path = $this->payment_proof->store('payment-proofs', 'public');
                    $order->update(['payment_proof' => $path]);
                }
                return $this->clearCartAndRedirect($order);
            }

            // For digital payments (M-Pesa, e-Mola, Card)
            $result = $this->paymentService->processPayment($order, $this->payment_method, [
                'phone' => $this->customer_phone,
            ]);

            if ($result['success']) {
                // For card payments, we need to handle Stripe client-side
                if ($this->payment_method === 'card' && isset($result['client_secret'])) {
                    $this->stripeClientSecret = $result['client_secret'];
                    $order->update(['transaction_id' => $result['payment_intent_id']]);
                    $this->processing = false;
                    $this->paymentMessage = 'Complete o pagamento com seu cartão.';
                    $this->dispatch('initStripe', clientSecret: $result['client_secret']);
                    return;
                }

                // For M-Pesa and e-Mola, open popup and poll for confirmation
                $this->pendingOrderId = $order->id;
                $this->popupOpen = true;
                $this->popupState = 'awaiting';
                $merchant = \App\Models\Setting::get('site_name', config('app.name'));
                $this->popupTitle = $this->payment_method === 'mpesa' ? 'Confirme no M-Pesa' : 'Confirme no e-Mola';
                $this->popupBody = $result['message'] ?? "Enviámos uma notificação a {$merchant} para {$this->customer_phone}. Confirme o pagamento no seu telefone.";
                $this->pollCount = 0;
                $this->processing = false;
                return;
            }

            // Payment failed
            $this->paymentError = $result['message'] ?? 'Erro ao processar pagamento.';
            $order->update(['payment_status' => 'failed']);
            $this->processing = false;

        } catch (\Exception $e) {
            $this->paymentError = 'Erro ao processar pedido: ' . $e->getMessage();
            $this->processing = false;
        }
    }

    /**
     * Called by wire:poll from the popup while waiting for payment confirmation.
     * Demo gateways: auto-mark as paid after 2 polls (~4s) so the flow can be tested without a webhook.
     */
    public function pollPaymentStatus()
    {
        if (!$this->popupOpen || $this->popupState !== 'awaiting' || !$this->pendingOrderId) {
            return;
        }

        $this->pollCount++;
        $order = Order::find($this->pendingOrderId);
        if (!$order || $order->user_id !== auth()->id()) {
            return;
        }

        // Real gateway: webhook flips this to 'paid' independently.
        if ($order->payment_status === 'paid') {
            $this->popupState = 'success';
            $this->popupTitle = 'Pagamento confirmado';
            $this->popupBody = "Pedido #{$order->order_number} confirmado. Vais ser redirecionado…";
            $this->clearCart();
            $this->dispatch('payment-confirmed', orderId: $order->id);
            return;
        }
        if ($order->payment_status === 'failed') {
            $this->popupState = 'failed';
            $this->popupTitle = 'Pagamento falhou';
            $this->popupBody = 'O pagamento não foi confirmado. Tenta novamente ou escolhe outro método.';
            return;
        }

        if ($this->pollCount >= 60) { // ~2 min @ 2s poll
            $this->popupState = 'failed';
            $this->popupTitle = 'Tempo esgotado';
            $this->popupBody = 'Não recebemos confirmação a tempo. O teu pedido fica em pendente — podes tentar pagar de novo a partir de "Meus Pedidos".';
        }
    }

    public function closePopup(): void
    {
        $this->popupOpen = false;
        $this->popupState = 'idle';
        $this->popupTitle = '';
        $this->popupBody = '';
        $this->pendingOrderId = null;
        $this->pollCount = 0;
    }

    public function goToSuccess()
    {
        if (!$this->pendingOrderId) return;
        $order = Order::find($this->pendingOrderId);
        if ($order && $order->user_id === auth()->id()) {
            session()->flash('order_success', $order->order_number);
            return redirect()->route('order.success', $order->id);
        }
    }

    protected function clearCart(): void
    {
        if (auth()->check()) {
            CartItem::where('user_id', auth()->id())->delete();
        } else {
            CartItem::where('session_id', session()->getId())->delete();
        }
        $this->dispatch('cart-cleared');
    }

    public function confirmStripePayment($paymentIntentId)
    {
        if (!auth()->check()) {
            abort(403);
        }

        $order = Order::where('transaction_id', $paymentIntentId)
            ->where('user_id', auth()->id())
            ->first();

        if ($order) {
            $this->paymentService->markAsPaid($order, $paymentIntentId);
            return $this->clearCartAndRedirect($order);
        }

        abort(404);
    }

    protected function clearCartAndRedirect(Order $order)
    {
        $this->clearCart();
        session()->flash('order_success', $order->order_number);
        return redirect()->route('order.success', $order->id);
    }

    public function render()
    {
        return view('livewire.public.checkout-cart')->layout('components.layouts.shop');
    }
}
