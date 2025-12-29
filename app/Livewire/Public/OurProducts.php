<?php

namespace App\Livewire\Public;

use Livewire\Component;
use App\Models\Product;
use App\Models\Category;

class OurProducts extends Component
{
    public $selectedCategory = null;

    public function filterByCategory($categoryId)
    {
        $this->selectedCategory = $categoryId;
    }

    public function clearFilter()
    {
        $this->selectedCategory = null;
    }

    public function render()
    {
        $query = Product::with(['category'])
            ->where('is_active', true)
            ->where('is_company_product', true)
            ->where('approval_status', 'approved');

        if ($this->selectedCategory) {
            $query->where('category_id', $this->selectedCategory);
        }

        $products = $query->latest()->get();
        $categories = Category::withCount(['products' => function($query) {
            $query->where('is_active', true)
                  ->where('is_company_product', true)
                  ->where('approval_status', 'approved');
        }])->get();

        return view('livewire.public.our-products', [
            'products' => $products,
            'categories' => $categories,
        ])->layout('components.layouts.shop');
    }
}
