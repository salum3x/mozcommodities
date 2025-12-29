<?php

namespace App\Livewire\Supplier;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;

class MyProducts extends Component
{
    use WithFileUploads, WithPagination;

    public $showForm = false;
    public $editingProduct = null;
    public $confirmingDelete = null;

    // Form fields
    public $name = '';
    public $category_id = '';
    public $description = '';
    public $price_per_kg = '';
    public $stock_quantity = '';
    public $min_quantity = 1;
    public $unit = 'kg';
    public $image;
    public $is_active = true;

    // Filters
    public $search = '';
    public $statusFilter = '';

    protected $rules = [
        'name' => 'required|min:3|max:255',
        'category_id' => 'required|exists:categories,id',
        'description' => 'nullable|max:1000',
        'price_per_kg' => 'required|numeric|min:0',
        'stock_quantity' => 'required|numeric|min:0',
        'min_quantity' => 'required|numeric|min:1',
        'unit' => 'required|in:kg,g,unidade,litro,saco',
        'image' => 'nullable|image|max:2048',
        'is_active' => 'boolean',
    ];

    public function mount()
    {
        if (request()->has('action') && request('action') === 'create') {
            $this->showForm = true;
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit($productId)
    {
        $supplier = auth()->user()->supplier;
        $product = Product::where('supplier_id', $supplier->id)->find($productId);

        if ($product) {
            $this->editingProduct = $product->id;
            $this->name = $product->name;
            $this->category_id = $product->category_id;
            $this->description = $product->description;
            $this->price_per_kg = $product->price_per_kg;
            $this->stock_quantity = $product->stock_quantity ?? 0;
            $this->min_quantity = $product->min_quantity ?? 1;
            $this->unit = $product->unit ?? 'kg';
            $this->is_active = $product->is_active;
            $this->showForm = true;
        }
    }

    public function save()
    {
        $this->validate();

        $supplier = auth()->user()->supplier;

        if (!$supplier) {
            session()->flash('error', 'Perfil de fornecedor não encontrado.');
            return;
        }

        $data = [
            'supplier_id' => $supplier->id,
            'name' => $this->name,
            'slug' => Str::slug($this->name) . '-' . uniqid(),
            'category_id' => $this->category_id,
            'description' => $this->description,
            'price_per_kg' => $this->price_per_kg,
            'stock_quantity' => $this->stock_quantity,
            'min_quantity' => $this->min_quantity,
            'unit' => $this->unit,
            'is_active' => $this->is_active,
            'approval_status' => 'pending',
        ];

        if ($this->image) {
            $data['image'] = $this->image->store('products', 'public');
        }

        if ($this->editingProduct) {
            $product = Product::find($this->editingProduct);
            if ($product && $product->supplier_id === $supplier->id) {
                unset($data['slug']); // Don't update slug
                unset($data['approval_status']); // Keep existing approval status
                $product->update($data);
                session()->flash('success', 'Produto atualizado com sucesso!');
            }
        } else {
            Product::create($data);
            session()->flash('success', 'Produto criado com sucesso! Aguarde aprovação do administrador.');
        }

        $this->resetForm();
        $this->showForm = false;
    }

    public function confirmDelete($productId)
    {
        $this->confirmingDelete = $productId;
    }

    public function delete()
    {
        $supplier = auth()->user()->supplier;
        $product = Product::where('supplier_id', $supplier->id)->find($this->confirmingDelete);

        if ($product) {
            $product->delete();
            session()->flash('success', 'Produto eliminado com sucesso!');
        }

        $this->confirmingDelete = null;
    }

    public function toggleActive($productId)
    {
        $supplier = auth()->user()->supplier;
        $product = Product::where('supplier_id', $supplier->id)->find($productId);

        if ($product) {
            $product->update(['is_active' => !$product->is_active]);
        }
    }

    public function cancelDelete()
    {
        $this->confirmingDelete = null;
    }

    public function cancel()
    {
        $this->resetForm();
        $this->showForm = false;
    }

    private function resetForm()
    {
        $this->editingProduct = null;
        $this->name = '';
        $this->category_id = '';
        $this->description = '';
        $this->price_per_kg = '';
        $this->stock_quantity = '';
        $this->min_quantity = 1;
        $this->unit = 'kg';
        $this->image = null;
        $this->is_active = true;
    }

    public function render()
    {
        $supplier = auth()->user()->supplier;

        $query = $supplier
            ? Product::where('supplier_id', $supplier->id)->with('category')
            : Product::where('id', 0); // Empty query if no supplier

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        if ($this->statusFilter === 'active') {
            $query->where('is_active', true);
        } elseif ($this->statusFilter === 'inactive') {
            $query->where('is_active', false);
        } elseif ($this->statusFilter === 'pending') {
            $query->where('approval_status', 'pending');
        }

        $products = $query->latest()->paginate(10);
        $categories = Category::where('is_active', true)->orderBy('name')->get();

        return view('livewire.supplier.my-products', [
            'products' => $products,
            'categories' => $categories,
            'supplier' => $supplier,
        ])->layout('components.layouts.supplier', ['title' => 'Meus Produtos']);
    }
}
