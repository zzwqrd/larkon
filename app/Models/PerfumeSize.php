<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerfumeSize extends Model
{
    use HasFactory;

    protected $table = 'perfume_sizes';

    protected $fillable = [
        'name',
        'name_en',
        'volume_ml',
        'priority',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'priority' => 'integer',
        'volume_ml' => 'integer',
    ];

    public function getNameAttribute($value)
    {
        if (app()->getLocale() == 'en' && !empty($this->name_en)) {
            return $this->name_en;
        }
        return $value;
    }
}
