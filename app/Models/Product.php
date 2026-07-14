<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'supplier_id',
        'is_company_product',
        'category_id',
        'name',
        'slug',
        'description',
        'price_per_kg',
        'cost_price',
        'platform_margin',
        'unit',
        'stock_quantity',
        'stock_kg',
        'min_quantity',
        'image',
        'is_active',
        'approval_status',
        'rejection_reason',
    ];

    protected $casts = [
        'price_per_kg' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'platform_margin' => 'decimal:2',
        'is_active' => 'boolean',
        'is_company_product' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

    public function getTotalStockAttribute()
    {
        return $this->stocks()->sum('quantity');
    }

    // Método para buscar o produto com maior preço por categoria/nome
    public static function getHighestPriceProduct($categoryId, $productName = null)
    {
        return self::where('category_id', $categoryId)
            ->where('is_active', true)
            ->when($productName, function ($query, $productName) {
                return $query->where('name', 'like', "%{$productName}%");
            })
            ->orderBy('price_per_kg', 'desc')
            ->first();
    }

    /**
     * Outras ofertas do mesmo produto (mesmo nome + categoria) de outros fornecedores.
     * Inclui esta linha. Ordenado do mais caro para o mais barato.
     */
    public function scopeSameProductOffers($query)
    {
        return $query;
    }

    public function siblingOffers()
    {
        return static::with(['supplier.user'])
            ->where('category_id', $this->category_id)
            ->whereRaw('LOWER(TRIM(name)) = ?', [mb_strtolower(trim($this->name))])
            ->where('is_active', true)
            ->where('approval_status', 'approved')
            ->orderByDesc('price_per_kg');
    }

    /**
     * Returns the URL to display for this product.
     * Priority:
     *   1. Supplier-uploaded image (storage/) — overrides
     *   2. Curated Unsplash fallback by name keyword
     *   3. Category-based fallback
     *   4. null (view renders SVG placeholder)
     */
    /**
     * Image URL for display. Returns null when no supplier upload exists —
     * the view then renders a category-themed SVG placeholder. We avoid
     * auto-fetched stock photos because they're unreliable for niche local
     * products (Moçambique-specific commodities).
     */
    public function getDisplayImageAttribute(): ?string
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return null;
    }

    /**
     * Emoji + colour for the category-based placeholder shown when no image is uploaded.
     */
    public function getPlaceholderAttribute(): array
    {
        $name = mb_strtolower(\Illuminate\Support\Str::ascii($this->name ?? ''));

        // Specific-product overrides (Mozambique staples first)
        $byName = [
            'milho'    => ['🌽', 'from-yellow-100 to-amber-200', 'text-amber-700'],
            'gergelim' => ['🌱', 'from-amber-100 to-orange-200', 'text-orange-700'],
            'feijao'   => ['🫘', 'from-red-100 to-rose-200', 'text-rose-700'],
            'arroz'    => ['🌾', 'from-stone-100 to-amber-100', 'text-stone-700'],
            'trigo'    => ['🌾', 'from-yellow-100 to-amber-200', 'text-amber-700'],
            'amendoim' => ['🥜', 'from-orange-100 to-amber-200', 'text-orange-800'],
            'mandioca' => ['🥔', 'from-stone-100 to-amber-100', 'text-stone-700'],
            'batata'   => ['🥔', 'from-yellow-50 to-amber-100', 'text-amber-800'],
            'cebola'   => ['🧅', 'from-amber-50 to-orange-100', 'text-orange-700'],
            'alho'     => ['🧄', 'from-stone-50 to-stone-200', 'text-stone-700'],
            'tomate'   => ['🍅', 'from-red-50 to-red-200', 'text-red-700'],
            'banana'   => ['🍌', 'from-yellow-50 to-yellow-200', 'text-yellow-700'],
            'manga'    => ['🥭', 'from-orange-50 to-red-100', 'text-orange-700'],
            'laranja'  => ['🍊', 'from-orange-50 to-orange-200', 'text-orange-700'],
            'limao'    => ['🍋', 'from-yellow-50 to-yellow-200', 'text-yellow-700'],
            'ananas'   => ['🍍', 'from-yellow-50 to-amber-200', 'text-amber-700'],
            'abacaxi'  => ['🍍', 'from-yellow-50 to-amber-200', 'text-amber-700'],
            'cana'     => ['🎋', 'from-green-50 to-emerald-100', 'text-emerald-700'],
            'girassol' => ['🌻', 'from-yellow-100 to-amber-200', 'text-amber-700'],
            'soja'     => ['🌱', 'from-emerald-50 to-green-200', 'text-emerald-700'],
            'algodao'  => ['☁️', 'from-stone-50 to-slate-100', 'text-slate-700'],
            'castanha' => ['🌰', 'from-amber-100 to-stone-200', 'text-stone-700'],
            'mapira'   => ['🌾', 'from-amber-100 to-yellow-200', 'text-amber-700'],
            'sorgo'    => ['🌾', 'from-amber-100 to-yellow-200', 'text-amber-700'],
            'mexoeira' => ['🌾', 'from-yellow-50 to-amber-200', 'text-amber-700'],
        ];

        foreach ($byName as $needle => $info) {
            $needleAscii = mb_strtolower(\Illuminate\Support\Str::ascii($needle));
            if (str_contains($name, $needleAscii)) {
                return ['emoji' => $info[0], 'bg' => $info[1], 'text' => $info[2]];
            }
        }

        // Category fallback
        return match ($this->category?->slug) {
            'cereais'     => ['emoji' => '🌾', 'bg' => 'from-amber-50 to-yellow-100', 'text' => 'text-amber-700'],
            'leguminosas' => ['emoji' => '🫘', 'bg' => 'from-red-50 to-rose-100', 'text' => 'text-rose-700'],
            'oleaginosas' => ['emoji' => '🌻', 'bg' => 'from-orange-50 to-amber-100', 'text' => 'text-orange-700'],
            'tuberculos'  => ['emoji' => '🥔', 'bg' => 'from-stone-50 to-amber-100', 'text' => 'text-stone-700'],
            'frutas'      => ['emoji' => '🍎', 'bg' => 'from-red-50 to-orange-100', 'text' => 'text-orange-700'],
            'horticolas'  => ['emoji' => '🥬', 'bg' => 'from-green-50 to-emerald-100', 'text' => 'text-emerald-700'],
            default       => ['emoji' => '🌱', 'bg' => 'from-emerald-50 to-green-100', 'text' => 'text-emerald-700'],
        };
    }
}
