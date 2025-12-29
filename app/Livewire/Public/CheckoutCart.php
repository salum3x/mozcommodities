<?php

namespace App\Livewire\Public;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CartItem;
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

    public function mount()
    {
        $this->cartItems = CartItem::getCartItems();

        if ($this->cartItems->isEmpty()) {
            return redirect()->route('cart');
        }

        $this->total = CartItem::getCartTotal();

        // Pre-fill if authenticated
        if (auth()->check()) {
            $this->customer_name = auth()->user()->name;
            $this->customer_email = auth()->user()->email;
            $this->customer_phone = auth()->user()->phone ?? '';
            $this->customer_address = auth()->user()->address ?? '';
        }
    }

    public function placeOrder()
    {
        $this->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email',
            'customer_phone' => 'required|string|max:20',
            'payment_method' => 'required|in:mpesa,bank_transfer',
            'payment_proof' => $this->payment_method === 'bank_transfer' ? 'required|image|max:2048' : 'nullable',
        ]);

        $orderNumber = 'ORD-' . strtoupper(uniqid());

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

        // Save payment proof if bank transfer
        if ($this->payment_method === 'bank_transfer' && $this->payment_proof) {
            $path = $this->payment_proof->store('payment-proofs', 'public');
            $order->update(['payment_proof' => $path]);
        }

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

        // Clear cart
        if (auth()->check()) {
            CartItem::where('user_id', auth()->id())->delete();
        } else {
            CartItem::where('session_id', session()->getId())->delete();
        }

        session()->flash('order_success', $orderNumber);

        return redirect()->route('order.success', $order->id);
    }

    public function render()
    {
        return view('livewire.public.checkout-cart')->layout('components.layouts.shop');
    }
}
