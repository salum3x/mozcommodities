<?php

namespace App\Livewire\Public;

use App\Models\Order;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class MyOrders extends Component
{
    use WithPagination;

    #[Url(as: 'tab')]
    public string $tab = 'all';

    #[Url(as: 'q')]
    public string $search = '';

    public ?int $expandedOrder = null;

    public function updatingTab(): void { $this->resetPage(); }
    public function updatingSearch(): void { $this->resetPage(); }

    public function toggleOrder(int $orderId): void
    {
        $this->expandedOrder = $this->expandedOrder === $orderId ? null : $orderId;
    }

    public function cancelOrder(int $orderId): void
    {
        $order = Order::where('id', $orderId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if (!in_array($order->status, ['pending', 'processing'], true)) {
            session()->flash('error', 'Este pedido já não pode ser cancelado.');
            return;
        }
        if ($order->payment_status === 'paid') {
            session()->flash('error', 'Pedidos pagos requerem solicitação de devolução.');
            return;
        }

        $order->update(['status' => 'cancelled']);
        session()->flash('message', "Pedido #{$order->order_number} cancelado.");
    }

    public function render()
    {
        $query = Order::with('items.product', 'returns')
            ->where('user_id', auth()->id())
            ->latest();

        if ($this->tab === 'pending') {
            $query->where('payment_status', 'pending')->whereNotIn('status', ['cancelled']);
        } elseif ($this->tab === 'paid') {
            $query->where('payment_status', 'paid');
        } elseif ($this->tab === 'cancelled') {
            $query->where('status', 'cancelled');
        }

        if ($this->search !== '') {
            $term = $this->search;
            $query->where(function ($q) use ($term) {
                $q->where('order_number', 'like', "%{$term}%")
                  ->orWhereHas('items', fn($i) => $i->where('product_name', 'like', "%{$term}%"));
            });
        }

        $orders = $query->paginate(10);

        $userId = auth()->id();
        $counts = [
            'all' => Order::where('user_id', $userId)->count(),
            'pending' => Order::where('user_id', $userId)->where('payment_status', 'pending')->whereNotIn('status', ['cancelled'])->count(),
            'paid' => Order::where('user_id', $userId)->where('payment_status', 'paid')->count(),
            'cancelled' => Order::where('user_id', $userId)->where('status', 'cancelled')->count(),
        ];

        return view('livewire.public.my-orders', [
            'orders' => $orders,
            'counts' => $counts,
        ])->layout('components.layouts.shop');
    }
}
