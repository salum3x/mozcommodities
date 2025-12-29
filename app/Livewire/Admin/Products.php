<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class Products extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $categoryFilter = '';
    public $statusFilter = '';

    // Form fields
    public $showForm = false;

    public function mount()
    {
        if (request()->has('action') && request('action') === 'create') {
            $this->create();
        }
    }
    public $editingId = null;
    public $name = '';
    public $description = '';
    public $category_id = '';
    public $supplier_id = '';
    public $price_per_kg = '';
    public $unit = 'kg';
    public $is_active = true;
    public $image;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string|max:1000',
        'category_id' => 'required|exists:categories,id',
        'supplier_id' => 'required|exists:suppliers,id',
        'price_per_kg' => 'required|numeric|min:0',
        'unit' => 'required|string|max:20',
        'is_active' => 'boolean',
        'image' => 'nullable|image|max:2048',
    ];

    public function create()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $this->editingId = $id;
        $this->name = $product->name;
        $this->description = $product->description;
        $this->category_id = $product->category_id;
        $this->supplier_id = $product->supplier_id;
        $this->price_per_kg = $product->price_per_kg;
        $this->unit = $product->unit;
        $this->is_active = $product->is_active;
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'description' => $this->description,
            'category_id' => $this->category_id,
            'supplier_id' => $this->supplier_id,
            'price_per_kg' => $this->price_per_kg,
            'unit' => $this->unit,
            'is_active' => $this->is_active,
        ];

        if ($this->image) {
            $data['image'] = $this->image->store('products', 'public');
        }

        if ($this->editingId) {
            $product = Product::findOrFail($this->editingId);
            $product->update($data);
            session()->flash('message', 'Produto atualizado com sucesso!');
        } else {
            Product::create($data);
            session()->flash('message', 'Produto criado com sucesso!');
        }

        $this->closeForm();
    }

    public function closeForm()
    {
        $this->showForm = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset(['editingId', 'name', 'description', 'category_id', 'supplier_id', 'price_per_kg', 'unit', 'is_active', 'image']);
        $this->is_active = true;
        $this->unit = 'kg';
    }

    public function toggleStatus($id)
    {
        $product = Product::findOrFail($id);
        $product->update(['is_active' => !$product->is_active]);
        session()->flash('message', 'Estado do produto atualizado!');
    }

    public function delete($id)
    {
        Product::findOrFail($id)->delete();
        session()->flash('message', 'Produto excluido com sucesso!');
    }

    public function render()
    {
        $products = Product::query()
            ->with(['category', 'supplier'])
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->when($this->categoryFilter, fn($q) => $q->where('category_id', $this->categoryFilter))
            ->when($this->statusFilter !== '', fn($q) => $q->where('is_active', $this->statusFilter === '1'))
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $categories = Category::orderBy('name')->get();
        $suppliers = Supplier::where('status', 'approved')->orderBy('company_name')->get();

        return view('livewire.admin.products', [
            'products' => $products,
            'categories' => $categories,
            'suppliers' => $suppliers,
        ])->layout('components.layouts.admin');
    }
}
