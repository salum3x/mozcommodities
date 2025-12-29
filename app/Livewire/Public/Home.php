<?php

namespace App\Livewire\Public;

use App\Models\Product;
use App\Models\Category;
use Livewire\Component;

class Home extends Component
{
    public function mount()
    {
        // Clientes podem ver a homepage normalmente
    }

    public function render()
    {
        // Todos os produtos misturados (empresa + fornecedores)
        $allProducts = Product::with(['supplier.user', 'category'])
            ->where('is_active', true)
            ->where('approval_status', 'approved')
            ->latest()
            ->take(9)
            ->get()
            ->groupBy('name')
            ->map(function ($group) {
                // Mostrar o produto com maior preço
                return $group->sortByDesc('price_per_kg')->first();
            })
            ->values()
            ->take(6);

        $categories = Category::where('is_active', true)
            ->withCount('products')
            ->take(4)
            ->get();

        return view('livewire.public.home', [
            'products' => $allProducts,
            'categories' => $categories,
        ])->layout('components.layouts.shop');
    }
}
