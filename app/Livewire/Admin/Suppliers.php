<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Supplier;
use App\Models\User;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;

class Suppliers extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';

    // Form fields
    public $showModal = false;
    public $editingId = null;
    public $company_name = '';
    public $document_number = '';
    public $whatsapp = '';
    public $address = '';
    public $description = '';
    public $status = 'pending';

    // User fields for new supplier
    public $user_name = '';
    public $user_email = '';
    public $user_password = '';

    protected function rules()
    {
        $rules = [
            'company_name' => 'required|string|max:255',
            'document_number' => 'nullable|string|max:50',
            'whatsapp' => 'required|string|max:20',
            'address' => 'nullable|string|max:500',
            'description' => 'nullable|string|max:1000',
            'status' => 'required|in:pending,approved,rejected',
        ];

        if (!$this->editingId) {
            $rules['user_name'] = 'required|string|max:255';
            $rules['user_email'] = 'required|email|unique:users,email';
            $rules['user_password'] = 'required|min:6';
        }

        return $rules;
    }

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit($id)
    {
        $supplier = Supplier::with('user')->findOrFail($id);
        $this->editingId = $id;
        $this->company_name = $supplier->company_name;
        $this->document_number = $supplier->document_number;
        $this->whatsapp = $supplier->whatsapp;
        $this->address = $supplier->address;
        $this->description = $supplier->description;
        $this->status = $supplier->status;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->editingId) {
            $supplier = Supplier::findOrFail($this->editingId);
            $supplier->update([
                'company_name' => $this->company_name,
                'document_number' => $this->document_number,
                'whatsapp' => $this->whatsapp,
                'address' => $this->address,
                'description' => $this->description,
                'status' => $this->status,
            ]);
            session()->flash('message', 'Fornecedor atualizado com sucesso!');
        } else {
            // Create user first
            $user = User::create([
                'name' => $this->user_name,
                'email' => $this->user_email,
                'password' => Hash::make($this->user_password),
                'role' => 'supplier',
            ]);

            // Create supplier
            Supplier::create([
                'user_id' => $user->id,
                'company_name' => $this->company_name,
                'document_number' => $this->document_number,
                'whatsapp' => $this->whatsapp,
                'address' => $this->address,
                'description' => $this->description,
                'status' => $this->status,
            ]);
            session()->flash('message', 'Fornecedor criado com sucesso!');
        }

        $this->closeModal();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset(['editingId', 'company_name', 'document_number', 'whatsapp', 'address', 'description', 'status', 'user_name', 'user_email', 'user_password']);
        $this->status = 'pending';
    }

    public function updateStatus($id, $status)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->update(['status' => $status]);
        session()->flash('message', 'Estado do fornecedor atualizado!');
    }

    public function delete($id)
    {
        Supplier::findOrFail($id)->delete();
        session()->flash('message', 'Fornecedor excluido com sucesso!');
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
