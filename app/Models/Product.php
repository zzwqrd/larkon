<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'size',
        'price',
        'stock',
        'sold',
        'category',
        'rating',
        'reviews_count',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'rating' => 'float',
        'is_active' => 'boolean',
    ];
}
