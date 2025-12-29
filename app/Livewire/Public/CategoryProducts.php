<?php

namespace App\Livewire\Public;

use Livewire\Component;
use App\Models\Product;
use App\Models\Category;

class CategoryProducts extends Component
{
    public $category;
    public $search = '';
    public $sortBy = 'latest';

    public function mount($slug)
    {
        $this->category = Category::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();
    }

    public function updatedSearch()
    {
        // Triggered when search changes
    }

    public function setSortBy($sort)
    {
        $this->sortBy = $sort;
    }

    public function render()
    {
        $query = Product::with(['supplier.user', 'category'])
            ->where('category_id', $this->category->id)
            ->where('is_active', true)
            ->where('approval_status', 'approved');

        // Search filter
        if ($this->search) {
            $query->where('name', 'like', "%{$this->search}%");
        }

        // Sort
        switch ($this->sortBy) {
            case 'price_asc':
                $query->orderBy('price_per_kg', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price_per_kg', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            default:
                $query->latest();
        }

        $products = $query->get();

        // Get all categories for sidebar
        $categories = Category::where('is_active', true)
            ->withCount(['products' => function($query) {
                $query->where('is_active', true)
                      ->where('approval_status', 'approved');
            }])
            ->orderBy('name')
            ->get();

        return view('livewire.public.category-products', [
            'products' => $products,
            'categories' => $categories,
        ])->layout('components.layouts.shop');
    }
}
