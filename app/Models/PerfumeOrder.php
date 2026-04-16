<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerfumeOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'admin_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'shipping_address',
        'shipping_city',
        'subtotal',
        'discount_amount',
        'shipping_cost',
        'total',
        'status',
        'payment_method',
        'payment_status',
        'admin_note',
    ];

    /**
     * Relationship with Order Items
     */
    public function items()
    {
        return $this->hasMany(PerfumeOrderItem::class, 'order_id');
    }

    /**
     * Relationship with Status History
     */
    public function statusHistory()
    {
        return $this->hasMany(PerfumeOrderStatusHistory::class, 'order_id')->latest();
    }

    /**
     * Relationship with Admin (Manager)
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }
}
