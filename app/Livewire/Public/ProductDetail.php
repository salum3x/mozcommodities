<?php

namespace App\Livewire\Public;

use App\Models\CartItem;
use App\Models\Product;
use Livewire\Attributes\Locked;
use Livewire\Component;

class ProductDetail extends Component
{
    #[Locked]
    public int $productId;

    #[Locked]
    public int $selectedOfferId;

    public int $quantity = 1;

    public string $notice = '';
    public string $error = '';

    public function mount(string $slug)
    {
        $product = Product::with(['supplier.user', 'category'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->where('approval_status', 'approved')
            ->firstOrFail();

        $this->productId = $product->id;

        $offers = $product->siblingOffers()->get();
        $this->selectedOfferId = $offers->first()->id;

        $this->quantity = max(1, (int) $product->min_quantity);
    }

    public function selectOffer(int $offerId): void
    {
        $product = Product::findOrFail($this->productId);
        $valid = $product->siblingOffers()->pluck('id')->contains($offerId);
        if (!$valid) {
            abort(404);
        }
        $this->selectedOfferId = $offerId;
    }

    public function addToCart()
    {
        $this->validate([
            'quantity' => 'required|integer|min:1|max:100000',
        ]);

        $offer = Product::with('supplier')->findOrFail($this->selectedOfferId);

        if (!$offer->is_active || $offer->approval_status !== 'approved') {
            $this->setError('Esta oferta já não está disponível.');
            return;
        }
        if ($this->quantity < (int) $offer->min_quantity) {
            $this->setError("Quantidade mínima: {$offer->min_quantity} {$offer->unit}.");
            return;
        }
        if ($this->quantity > (int) $offer->stock_quantity) {
            $this->setError("Stock insuficiente. Disponível: {$offer->stock_quantity} {$offer->unit}.");
            return;
        }

        $existing = CartItem::where('product_id', $offer->id)
            ->when(auth()->check(),
                fn($q) => $q->where('user_id', auth()->id()),
                fn($q) => $q->whereNull('user_id')->where('session_id', session()->getId())
            )
            ->first();

        if ($existing) {
            $newQty = $existing->quantity + $this->quantity;
            if ($newQty > $offer->stock_quantity) {
                $this->setError("Stock insuficiente. Disponível: {$offer->stock_quantity} {$offer->unit}.");
                return;
            }
            $existing->update([
                'quantity' => $newQty,
                'price_per_kg' => $offer->price_per_kg,
            ]);
        } else {
            CartItem::create([
                'user_id' => auth()->id(),
                'session_id' => auth()->check() ? null : session()->getId(),
                'product_id' => $offer->id,
                'quantity' => $this->quantity,
                'price_per_kg' => $offer->price_per_kg,
            ]);
        }

        $this->dispatch('cart-updated');
        $this->dispatch('product-added-to-cart', productName: $offer->name);
        $this->notice = "Adicionado: {$this->quantity} {$offer->unit} de {$offer->name}.";
        $this->error = '';
    }

    public function incrementQuantity(): void
    {
        $offer = Product::find($this->selectedOfferId);
        $max = $offer ? (int) $offer->stock_quantity : 100000;
        if ($this->quantity < $max) {
            $this->quantity++;
        }
    }

    public function decrementQuantity(): void
    {
        $offer = Product::find($this->selectedOfferId);
        $min = $offer ? max(1, (int) $offer->min_quantity) : 1;
        if ($this->quantity > $min) {
            $this->quantity--;
        }
    }

    protected function setError(string $msg): void
    {
        $this->error = $msg;
        $this->notice = '';
        $this->dispatch('cart-error', text: $msg);
    }

    public function render()
    {
        $product = Product::with(['supplier.user', 'category'])->findOrFail($this->productId);
        $offers = $product->siblingOffers()->get();
        $selected = $offers->firstWhere('id', $this->selectedOfferId) ?? $offers->first();

        return view('livewire.public.product-detail', [
            'product' => $product,
            'offers' => $offers,
            'selected' => $selected,
            'highestPrice' => $offers->max('price_per_kg'),
            'lowestPrice' => $offers->min('price_per_kg'),
        ])->layout('components.layouts.shop');
    }
}
