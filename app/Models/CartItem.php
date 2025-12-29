<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    protected $fillable = [
        'user_id',
        'session_id',
        'product_id',
        'quantity',
        'price_per_kg',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getSubtotalAttribute(): float
    {
        return $this->quantity * $this->price_per_kg;
    }

    public static function getCartItems()
    {
        if (auth()->check()) {
            return self::where('user_id', auth()->id())
                ->with('product')
                ->get();
        } else {
            return self::where('session_id', session()->getId())
                ->with('product')
                ->get();
        }
    }

    public static function getCartCount(): int
    {
        if (auth()->check()) {
            return self::where('user_id', auth()->id())->sum('quantity');
        } else {
            return self::where('session_id', session()->getId())->sum('quantity');
        }
    }

    public static function getCartTotal(): float
    {
        $items = self::getCartItems();
        return $items->sum(function ($item) {
            return $item->quantity * $item->price_per_kg;
        });
    }
}
