<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductRequest extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'product_name',
        'description',
        'quantity_kg',
        'status',
        'admin_notes',
    ];
}
