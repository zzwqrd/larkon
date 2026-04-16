<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerfumeOrderStatusHistory extends Model
{
    use HasFactory;

    protected $table = 'perfume_order_status_history';

    protected $fillable = [
        'order_id',
        'status',
        'note',
        'changed_by',
        'notify_customer',
    ];

    /**
     * Relationship with Order
     */
    public function order()
    {
        return $this->belongsTo(PerfumeOrder::class, 'order_id');
    }

    /**
     * Relationship with Admin who changed the status
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'changed_by');
    }
}
