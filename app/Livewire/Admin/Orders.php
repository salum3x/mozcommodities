<?php

namespace App\Livewire\Admin;

use App\Models\Order;
use App\Services\Payment\PaymentService;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Orders extends Component
{
    use WithPagination;

    #[Url(as: 'status')]
    public string $filter = 'pending';

    #[Url(as: 'q')]
    public string $search = '';

    public ?int $expanded = null;
    public string $adminNote = '';

    public function updatingFilter(): void { $this->resetPage(); }
    public function updatingSearch(): void { $this->resetPage(); }

    public function expand(int $id): void
    {
        if ($this->expanded === $id) {
            $this->expanded = null;
            $this->adminNote = '';
            return;
        }
        $this->expanded = $id;
        $this->adminNote = '';
    }

    public function markAsPaid(int $id): void
    {
        $this->ensureAdmin();
        $order = Order::findOrFail($id);
        if ($order->payment_status === 'paid') return;

        $svc = app(PaymentService::class);
        $svc->markAsPaid($order, $order->transaction_id ?: 'MANUAL-' . $order->id);

        if ($this->adminNote !== '') {
            $order->update(['notes' => trim(($order->notes ?? '') . "\n[ADMIN " . now()->format('Y-m-d H:i') . "] " . $this->adminNote)]);
        }
        session()->flash('message', "Pedido #{$order->order_number} marcado como pago.");
    }

    public function markAsFailed(int $id): void
    {
        $this->ensureAdmin();
        $order = Order::findOrFail($id);
        if (!$this->adminNote) {
            session()->flash('error', 'Indica o motivo da falha nas notas.');
            return;
        }
        $order->update([
            'payment_status' => 'failed',
            'notes' => trim(($order->notes ?? '') . "\n[ADMIN " . now()->format('Y-m-d H:i') . "] Falhou: " . $this->adminNote),
        ]);
        session()->flash('message', "Pedido #{$order->order_number} marcado como falhou.");
    }

    public function advanceStatus(int $id, string $next): void
    {
        $this->ensureAdmin();
        $valid = ['processing', 'completed', 'cancelled'];
        if (!in_array($next, $valid, true)) return;
        $order = Order::findOrFail($id);
        $order->update(['status' => $next]);
        session()->flash('message', "Pedido #{$order->order_number} → {$next}.");
    }

    protected function ensureAdmin(): void
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') abort(403);
    }

    public function render()
    {
        $query = Order::with('items.product', 'user')->latest();

        if (in_array($this->filter, ['pending', 'paid', 'failed'], true)) {
            $query->where('payment_status', $this->filter);
        } elseif ($this->filter === 'cancelled') {
            $query->where('status', 'cancelled');
        } elseif ($this->filter === 'processing') {
            $query->where('status', 'processing');
        }

        if ($this->search !== '') {
            $term = $this->search;
            $query->where(function ($q) use ($term) {
                $q->where('order_number', 'like', "%{$term}%")
                  ->orWhere('customer_email', 'like', "%{$term}%")
                  ->orWhere('customer_phone', 'like', "%{$term}%")
                  ->orWhere('transaction_id', 'like', "%{$term}%");
            });
        }

        $counts = [
            'pending'    => Order::where('payment_status', 'pending')->count(),
            'paid'       => Order::where('payment_status', 'paid')->count(),
            'failed'     => Order::where('payment_status', 'failed')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'cancelled'  => Order::where('status', 'cancelled')->count(),
        ];

        return view('livewire.admin.orders', [
            'orders' => $query->paginate(15),
            'counts' => $counts,
        ])->layout('components.layouts.admin');
    }
}
