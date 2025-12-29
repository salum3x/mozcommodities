<?php

namespace App\Livewire\Public;

use Livewire\Component;
use App\Models\CartItem;
use App\Models\Product;

class AddToCart extends Component
{
    public $productId;
    public $quantity = 1;
    public $showQuantityInput = false;

    public function mount($productId)
    {
        $this->productId = $productId;
    }

    public function toggleQuantityInput()
    {
        $this->showQuantityInput = !$this->showQuantityInput;
    }

    public function addToCart()
    {
        $this->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($this->productId);

        $cartItem = CartItem::where('product_id', $this->productId);

        if (auth()->check()) {
            $cartItem = $cartItem->where('user_id', auth()->id());
        } else {
            $cartItem = $cartItem->where('session_id', session()->getId());
        }

        $cartItem = $cartItem->first();

        if ($cartItem) {
            // Atualizar quantidade
            $cartItem->increment('quantity', $this->quantity);
        } else {
            // Criar novo item
            CartItem::create([
                'user_id' => auth()->id(),
                'session_id' => auth()->check() ? null : session()->getId(),
                'product_id' => $this->productId,
                'quantity' => $this->quantity,
                'price_per_kg' => $product->price_per_kg,
            ]);
        }

        $this->dispatch('cart-updated');
        $this->dispatch('product-added-to-cart', productName: $product->name);

        // Reset
        $this->quantity = 1;
        $this->showQuantityInput = false;
    }

    public function render()
    {
        return view('livewire.public.add-to-cart');
    }
}
