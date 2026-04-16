<?php

namespace App\Models;

use App\Traits\UploadTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerfumeReturnRequest extends Model
{
    use HasFactory, UploadTrait;

    protected $table = 'perfume_return_requests';

    protected $fillable = [
        'order_id',
        'user_id',
        'return_number',
        'items_to_return',
        'reason',
        'reason_details',
        'image_1',
        'image_2',
        'image_3',
        'status',
        'admin_note',
    ];

    protected $casts = [
        'items_to_return' => 'array',
    ];

    public function order()
    {
        return $this->belongsTo(PerfumeOrder::class, 'order_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
