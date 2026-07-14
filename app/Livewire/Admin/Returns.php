<?php

namespace App\Livewire\Admin;

use App\Models\OrderReturn;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Returns extends Component
{
    use WithPagination;

    #[Url(as: 'status')]
    public string $filter = 'pending';

    #[Url(as: 'q')]
    public string $search = '';

    public ?int $expanded = null;
    public string $adminNotes = '';

    public function updatingFilter(): void { $this->resetPage(); }
    public function updatingSearch(): void { $this->resetPage(); }

    public function expand(int $id): void
    {
        if ($this->expanded === $id) {
            $this->expanded = null;
            $this->adminNotes = '';
            return;
        }
        $this->expanded = $id;
        $ret = OrderReturn::find($id);
        $this->adminNotes = $ret->admin_notes ?? '';
    }

    public function approve(int $id): void
    {
        $this->ensureAdmin();
        $ret = OrderReturn::findOrFail($id);
        if ($ret->status !== 'pending') return;
        $ret->update([
            'status' => 'approved',
            'admin_notes' => $this->adminNotes ?: null,
            'resolved_at' => now(),
        ]);
        session()->flash('message', "Devolução {$ret->return_number} aprovada.");
    }

    public function reject(int $id): void
    {
        $this->ensureAdmin();
        $ret = OrderReturn::findOrFail($id);
        if ($ret->status !== 'pending') return;
        if (!$this->adminNotes) {
            session()->flash('error', 'Indica o motivo da rejeição nas notas internas.');
            return;
        }
        $ret->update([
            'status' => 'rejected',
            'admin_notes' => $this->adminNotes,
            'resolved_at' => now(),
        ]);
        session()->flash('message', "Devolução {$ret->return_number} rejeitada.");
    }

    public function refund(int $id): void
    {
        $this->ensureAdmin();
        $ret = OrderReturn::with('order')->findOrFail($id);
        if ($ret->status !== 'approved') return;
        $ret->update([
            'status' => 'refunded',
            'admin_notes' => $this->adminNotes ?: $ret->admin_notes,
            'resolved_at' => now(),
        ]);
        $ret->order?->update(['status' => 'cancelled']);
        session()->flash('message', "Reembolso processado para {$ret->return_number}.");
    }

    protected function ensureAdmin(): void
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403);
        }
    }

    public function render()
    {
        $query = OrderReturn::with('order', 'user')->latest();
        if (in_array($this->filter, ['pending', 'approved', 'rejected', 'refunded'], true)) {
            $query->where('status', $this->filter);
        }
        if ($this->search !== '') {
            $term = $this->search;
            $query->where(function ($q) use ($term) {
                $q->where('return_number', 'like', "%{$term}%")
                  ->orWhereHas('order', fn($o) => $o->where('order_number', 'like', "%{$term}%"))
                  ->orWhereHas('user', fn($u) => $u->where('email', 'like', "%{$term}%"));
            });
        }

        $counts = [
            'pending' => OrderReturn::where('status', 'pending')->count(),
            'approved' => OrderReturn::where('status', 'approved')->count(),
            'refunded' => OrderReturn::where('status', 'refunded')->count(),
            'rejected' => OrderReturn::where('status', 'rejected')->count(),
        ];

        return view('livewire.admin.returns', [
            'returns' => $query->paginate(15),
            'counts' => $counts,
        ])->layout('components.layouts.admin');
    }
}
