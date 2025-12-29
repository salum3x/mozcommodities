<?php

namespace App\Livewire\Public;

use App\Models\Product;
use App\Models\Category;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Home extends Component
{
    public function mount()
    {
        // Se o usuário está autenticado e é cliente, redirecionar para produtos
        if (Auth::check() && Auth::user()->isCustomer()) {
            return redirect()->route('products');
        }
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
        ]);
    }
}
