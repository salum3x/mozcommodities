<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Supplier;
use Livewire\WithPagination;

class Suppliers extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';

    public function updateStatus($id, $status)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->update(['status' => $status]);
        session()->flash('message', 'Estado do fornecedor atualizado!');
    }

    public function delete($id)
    {
        Supplier::findOrFail($id)->delete();
        session()->flash('message', 'Fornecedor excluído com sucesso!');
    }

    public function render()
    {
        $suppliers = Supplier::query()
            ->with(['user', 'products'])
            ->when($this->search, fn($q) => $q->where('company_name', 'like', "%{$this->search}%"))
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.suppliers', [
            'suppliers' => $suppliers,
        ])->layout('components.layouts.admin');
    }
}
