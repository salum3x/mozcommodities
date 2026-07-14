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
        $quantity = (int) $quantity;
        if ($quantity < 1 || $quantity > 100000) {
            return;
        }

        $cartItem = $this->findOwnedCartItem($cartItemId);
        if (!$cartItem) {
            abort(403);
        }

        $delta = $quantity - (int) $cartItem->quantity;
        $cartItem->update(['quantity' => $quantity]);
        $this->loadCart();
        if ($delta > 0) {
            $this->dispatch('cart-added', qty: $delta);
        } elseif ($delta < 0) {
            $this->dispatch('cart-removed', qty: abs($delta));
        }
        $this->dispatch('cart-updated');
    }

    public function removeItem($cartItemId)
    {
        $cartItem = $this->findOwnedCartItem($cartItemId);
        if (!$cartItem) {
            abort(403);
        }

        $removedQty = (int) $cartItem->quantity;
        $cartItem->delete();
        $this->loadCart();
        $this->dispatch('cart-removed', qty: $removedQty);
        $this->dispatch('cart-updated');
        session()->flash('message', 'Produto removido do carrinho.');
    }

    protected function findOwnedCartItem($cartItemId): ?CartItem
    {
        $query = CartItem::where('id', $cartItemId);
        if (auth()->check()) {
            $query->where('user_id', auth()->id());
        } else {
            $query->whereNull('user_id')->where('session_id', session()->getId());
        }
        return $query->first();
    }

    public function clearCart()
    {
        if (auth()->check()) {
            CartItem::where('user_id', auth()->id())->delete();
        } else {
            CartItem::where('session_id', session()->getId())->delete();
        }

        $this->loadCart();
        $this->dispatch('cart-cleared');
        $this->dispatch('cart-updated');
        session()->flash('message', 'Carrinho limpo com sucesso.');
    }

    public function render()
    {
        $weightKg = collect($this->cartItems)
            ->filter()
            ->sum(fn ($i) => ($i->product && in_array($i->product->unit, ['kg', null], true)) ? (float) $i->quantity : 0);

        $shipping = app(\App\Services\ShippingService::class)->estimate(
            destination: null,
            supplierUser: null,
            orderSubtotal: (float) $this->total,
            weightKg: (float) $weightKg,
        );

        return view('livewire.public.cart', [
            'shipping' => $shipping,
        ])->layout('components.layouts.shop');
    }
}
