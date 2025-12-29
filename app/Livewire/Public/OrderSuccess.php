<?php

namespace App\Livewire\Public;

use App\Models\Order;
use Livewire\Component;

class OrderSuccess extends Component
{
    public $order;

    public function mount($order)
    {
        $this->order = Order::with('items')->findOrFail($order);
    }

    public function render()
    {
        return view('livewire.public.order-success')->layout('components.layouts.app');
    }
}
