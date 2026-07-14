<?php

namespace App\Livewire\Public;

use App\Models\WishlistItem;
use Livewire\Component;

class Wishlist extends Component
{
    public function remove(int $itemId): void
    {
        $item = WishlistItem::where('user_id', auth()->id())->find($itemId);
        if ($item) {
            $item->delete();
            $this->dispatch('wishlist-changed');
            session()->flash('message', 'Removido dos favoritos.');
        }
    }

    public function render()
    {
        $items = WishlistItem::with('product.category', 'product.supplier')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('livewire.public.wishlist', [
            'items' => $items,
        ])->layout('components.layouts.shop');
    }
}
