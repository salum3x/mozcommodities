<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WishlistItem extends Model
{
    protected $fillable = ['user_id', 'product_id'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function toggle(int $userId, int $productId): bool
    {
        $existing = static::where('user_id', $userId)->where('product_id', $productId)->first();
        if ($existing) {
            $existing->delete();
            return false; // removed
        }
        static::create(['user_id' => $userId, 'product_id' => $productId]);
        return true; // added
    }
}
