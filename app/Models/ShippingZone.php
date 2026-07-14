<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingZone extends Model
{
    protected $fillable = [
        'province', 'city', 'base_fee', 'per_kg_rate',
        'truckload_threshold_kg', 'truckload_flat_fee',
        'free_above_amount', 'active', 'notes',
    ];

    protected $casts = [
        'base_fee' => 'decimal:2',
        'per_kg_rate' => 'decimal:2',
        'truckload_flat_fee' => 'decimal:2',
        'free_above_amount' => 'decimal:2',
        'truckload_threshold_kg' => 'integer',
        'active' => 'boolean',
    ];

    /**
     * Resolve a zone for a destination label (e.g. "Beira, Sofala" → matches Sofala).
     */
    public static function resolveFor(?string $label): ?self
    {
        if (!$label) return null;
        $needle = mb_strtolower(\Illuminate\Support\Str::ascii($label));

        // Iterate active zones; longer matches win
        $zones = static::where('active', true)->get()
            ->sortByDesc(fn ($z) => mb_strlen($z->province));

        foreach ($zones as $z) {
            $prov = mb_strtolower(\Illuminate\Support\Str::ascii($z->province));
            if (str_contains($needle, $prov)) return $z;
            if ($z->city && str_contains($needle, mb_strtolower(\Illuminate\Support\Str::ascii($z->city)))) {
                return $z;
            }
        }
        return null;
    }
}
