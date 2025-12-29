<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Livewire\WithPagination;

class Products extends Component
{
    use WithPagination;

    public $search = '';
    public $categoryFilter = '';
    public $statusFilter = '';

    public function toggleStatus($id)
    {
        $product = Product::findOrFail($id);
        $product->update(['is_active' => !$product->is_active]);
        session()->flash('message', 'Estado do produto atualizado!');
    }

    public function delete($id)
    {
        Product::findOrFail($id)->delete();
        session()->flash('message', 'Produto excluído com sucesso!');
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

        return view('livewire.admin.products', [
            'products' => $products,
            'categories' => $categories,
        ])->layout('components.layouts.admin');
    }
}
