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
}
