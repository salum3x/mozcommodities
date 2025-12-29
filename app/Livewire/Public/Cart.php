<?php

namespace App\Livewire\Public;

use Livewire\Component;
use App\Models\CartItem;

class Cart extends Component
{
    public $cartItems = [];
    public $total = 0;

    public function mount()
    {
        $this->loadCart();
    }

    public function loadCart()
    {
        $this->cartItems = CartItem::getCartItems();
        $this->total = CartItem::getCartTotal();
    }

    public function updateQuantity($cartItemId, $quantity)
    {
        if ($quantity < 1) {
            return;
        }

        $cartItem = CartItem::find($cartItemId);
        if ($cartItem) {
            $cartItem->update(['quantity' => $quantity]);
            $this->loadCart();
            $this->dispatch('cart-updated');
        }
    }

    public function removeItem($cartItemId)
    {
        $cartItem = CartItem::find($cartItemId);
        if ($cartItem) {
            $cartItem->delete();
            $this->loadCart();
            $this->dispatch('cart-updated');
            session()->flash('message', 'Produto removido do carrinho.');
        }
    }

    public function clearCart()
    {
        if (auth()->check()) {
            CartItem::where('user_id', auth()->id())->delete();
        } else {
            CartItem::where('session_id', session()->getId())->delete();
        }

        $this->loadCart();
        $this->dispatch('cart-updated');
        session()->flash('message', 'Carrinho limpo com sucesso.');
    }

    public function render()
    {
        return view('livewire.public.cart');
    }
}
