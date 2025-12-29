<?php

namespace App\Livewire\Public;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithFileUploads;

class Checkout extends Component
{
    use WithFileUploads;

    public $product_id;
    public $product;
    public $quantity = 1;

    // Customer info
    public $customer_name = '';
    public $customer_email = '';
    public $customer_phone = '';
    public $customer_address = '';

    // Payment
    public $payment_method = 'mpesa';
    public $payment_proof;
    public $notes = '';

    public function mount($product_id)
    {
        $this->product_id = $product_id;
        $this->product = Product::with(['supplier', 'category'])->findOrFail($product_id);

        // Pre-fill if authenticated
        if (auth()->check()) {
            $this->customer_name = auth()->user()->name;
            $this->customer_email = auth()->user()->email;
            $this->customer_phone = auth()->user()->phone ?? '';
        }
    }

    public function updatedQuantity()
    {
        if ($this->quantity < 1) {
            $this->quantity = 1;
        }
    }

    public function getSubtotalProperty()
    {
        return $this->product->price_per_kg * $this->quantity;
    }

    public function getTotalProperty()
    {
        return $this->subtotal;
    }

    public function placeOrder()
    {
        $this->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email',
            'customer_phone' => 'required|string|max:20',
            'quantity' => 'required|integer|min:1',
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
            'subtotal' => $this->subtotal,
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

        // Create order item
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'price' => $this->product->price_per_kg,
            'quantity' => $this->quantity,
            'subtotal' => $this->subtotal,
        ]);

        session()->flash('order_success', $orderNumber);

        return redirect()->route('order.success', $order->id);
    }

    public function render()
    {
        return view('livewire.public.checkout')->layout('components.layouts.shop');
    }
}
