<?php

namespace App\Livewire\Public;

use App\Models\Category;
use App\Models\Product;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Products extends Component
{
    use WithPagination;

    #[Url(as: 'category')]
    public $selectedCategory = null;

    #[Url(as: 'q')]
    public $search = '';

    #[Url(as: 'sort')]
    public string $sort = 'relevant';

    public function mount()
    {
        if (request()->has('category')) {
            $this->selectedCategory = request()->get('category');
        }
    }

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingSelectedCategory(): void { $this->resetPage(); }
    public function updatingSort(): void { $this->resetPage(); }

    public function filterByCategory($categoryId): void
    {
        $this->selectedCategory = $categoryId;
        $this->resetPage();
    }

    public function clearFilter(): void
    {
        $this->selectedCategory = null;
        $this->search = '';
        $this->sort = 'relevant';
        $this->resetPage();
    }

    public function render()
    {
        $query = Product::with(['supplier.user', 'category'])
            ->where('is_active', true)
            ->where('approval_status', 'approved');

        if ($this->selectedCategory) {
            $query->where('category_id', $this->selectedCategory);
        }
        if ($this->search) {
            $query->where('name', 'like', "%{$this->search}%");
        }

        $all = $query->get();

        // Group by normalized name + category to coalesce supplier offers into one card.
        // Use a plain stdClass wrapper instead of mutating Eloquent models — Eloquent's
        // __set treats dynamic properties as DB attributes and squashes the values.
        $grouped = $all->groupBy(fn($p) => mb_strtolower(trim($p->name)) . '|' . $p->category_id)
            ->map(function ($group) {
                $top = $group->sortByDesc('price_per_kg')->first();
                return (object) [
                    'product' => $top,
                    'offer_count' => $group->count(),
                    'min_price' => (float) $group->min('price_per_kg'),
                    'max_price' => (float) $group->max('price_per_kg'),
                    'total_stock' => (int) $group->sum('stock_quantity'),
                ];
            })
            ->values();

        $grouped = match ($this->sort) {
            'price_asc'  => $grouped->sortBy('min_price')->values(),
            'price_desc' => $grouped->sortByDesc('max_price')->values(),
            'name'       => $grouped->sortBy(fn($g) => mb_strtolower($g->product->name))->values(),
            'newest'     => $grouped->sortByDesc(fn($g) => $g->product->created_at)->values(),
            default      => $grouped,
        };

        $categories = Category::withCount(['products' => function ($q) {
            $q->where('is_active', true)->where('approval_status', 'approved');
        }])->orderBy('name')->get();

        return view('livewire.public.products', [
            'products' => $grouped,
            'categories' => $categories,
        ])->layout('components.layouts.shop');
    }
}
