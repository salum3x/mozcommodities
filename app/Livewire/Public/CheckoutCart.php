<?php

namespace App\Livewire\Public;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CartItem;
use App\Services\Payment\PaymentService;
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
    public $payment_method = 'mpesa';
    public $payment_proof;
    public $notes = '';

    // Payment processing
    public $processing = false;
    public $paymentError = '';
    public $paymentMessage = '';
    public $stripeClientSecret = '';
    public $availableGateways = [];

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
            'payment_method' => 'required|in:mpesa,emola,card,bank_transfer',
        ];

        // Require payment proof for bank transfer
        if ($this->payment_method === 'bank_transfer') {
            $rules['payment_proof'] = 'required|image|max:2048';
        }

        $this->validate($rules);

        try {
            $orderNumber = 'ORD-' . strtoupper(uniqid());

            // Create order
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

            // Create order items from cart
            foreach ($this->cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'product_name' => $cartItem->product->name,
                    'price' => $cartItem->price_per_kg,
                    'quantity' => $cartItem->quantity,
                    'subtotal' => $cartItem->quantity * $cartItem->price_per_kg,
                ]);
            }

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
                    $this->paymentMessage = 'Complete o pagamento com seu cartao.';
                    $this->dispatch('initStripe', clientSecret: $result['client_secret']);
                    return;
                }

                // For M-Pesa and e-Mola, payment is initiated
                $this->paymentMessage = $result['message'] ?? 'Pagamento iniciado. Confirme no seu telefone.';
                return $this->clearCartAndRedirect($order);
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

    public function confirmStripePayment($paymentIntentId)
    {
        $order = Order::where('transaction_id', $paymentIntentId)->first();

        if ($order) {
            $this->paymentService->markAsPaid($order, $paymentIntentId);
            return $this->clearCartAndRedirect($order);
        }
    }

    protected function clearCartAndRedirect(Order $order)
    {
        // Clear cart
        if (auth()->check()) {
            CartItem::where('user_id', auth()->id())->delete();
        } else {
            CartItem::where('session_id', session()->getId())->delete();
        }

        session()->flash('order_success', $order->order_number);

        return redirect()->route('order.success', $order->id);
    }

    public function render()
    {
        return view('livewire.public.checkout-cart')->layout('components.layouts.shop');
    }
}
