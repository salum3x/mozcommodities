<?php

namespace App\Livewire\Public;

use App\Models\Category;
use App\Models\Product;
use Livewire\Component;
use Livewire\Attributes\Url;

class Products extends Component
{
    #[Url]
    public $selectedCategory = null;

    public $search = '';

    public function mount()
    {
        // Captura o parâmetro 'category' da URL se existir
        if (request()->has('category')) {
            $this->selectedCategory = request()->get('category');
        }
    }

    public function filterByCategory($categoryId)
    {
        $this->selectedCategory = $categoryId;
    }

    public function clearFilter()
    {
        $this->selectedCategory = null;
        $this->search = '';
    }

    public function render()
    {
        // Busca todos os produtos aprovados (próprios + fornecedores)
        $query = Product::with(['supplier.user', 'category'])
            ->where('is_active', true)
            ->where('approval_status', 'approved');

        // Filtro por categoria
        if ($this->selectedCategory) {
            $query->where('category_id', $this->selectedCategory);
        }

        // Filtro por busca
        if ($this->search) {
            $query->where('name', 'like', "%{$this->search}%");
        }

        // Agrupa produtos por nome e retorna apenas o de maior preço
        $products = $query->get()
            ->groupBy('name')
            ->map(function ($group) {
                // Retorna o produto com maior preço de cada grupo
                return $group->sortByDesc('price_per_kg')->first();
            })
            ->values();

        // Categorias com contagem de produtos
        $categories = Category::withCount(['products' => function($query) {
            $query->where('is_active', true)
                  ->where('approval_status', 'approved');
        }])->get();

        return view('livewire.public.products', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }
}
