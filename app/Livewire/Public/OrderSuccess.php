<?php

namespace App\Livewire\Public;

use App\Models\Order;
use Livewire\Component;

class OrderSuccess extends Component
{
    public $order;

    public function mount($order)
    {
        $query = Order::with('items')->where('id', $order);

        if (auth()->check()) {
            $query->where('user_id', auth()->id());
        } else {
            $flashed = session('order_success');
            $candidate = $query->first();
            if (!$candidate || $candidate->order_number !== $flashed) {
                abort(403);
            }
            $this->order = $candidate;
            return;
        }

        $this->order = $query->firstOrFail();
    }

    public function render()
    {
        return view('livewire.public.order-success')->layout('components.layouts.shop');
    }
}
