<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerfumeReview extends Model
{
    use HasFactory;

    protected $table = 'perfume_reviews';

    protected $fillable = [
        'product_id',
        'user_id',
        'order_id',
        'reviewer_name',
        'rating',
        'comment',
        'status',
        'is_verified_purchase',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_verified_purchase' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function order()
    {
        return $this->belongsTo(PerfumeOrder::class, 'order_id');
    }
}
