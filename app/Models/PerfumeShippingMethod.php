<?php

namespace App\Models;

use App\Traits\UploadTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerfumeShippingMethod extends Model
{
    use HasFactory, UploadTrait;

    protected $table = 'perfume_shipping_methods';

    protected $fillable = [
        'name',
        'name_en',
        'logo',
        'base_cost',
        'free_shipping_above',
        'estimated_days',
        'type',
        'is_active',
        'priority',
    ];

    protected $casts = [
        'base_cost' => 'decimal:2',
        'free_shipping_above' => 'decimal:2',
        'estimated_days' => 'integer',
        'is_active' => 'boolean',
        'priority' => 'integer',
    ];

    public function getNameAttribute($value)
    {
        if (app()->getLocale() == 'en' && !empty($this->name_en)) {
            return $this->name_en;
        }
        return $value;
    }
}
