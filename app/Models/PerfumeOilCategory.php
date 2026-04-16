<?php

namespace App\Models;

use App\Traits\UploadTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerfumeOilCategory extends Model
{
    use HasFactory, UploadTrait;

    protected $table = 'perfume_oil_categories';

    protected $fillable = [
        'name',
        'name_en',
        'description',
        'description_en',
        'image',
        'banner_image',
        'priority',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'priority' => 'integer',
    ];

    /**
     * Relationship with Essential Oils
     */
    public function essentialOils()
    {
        return $this->hasMany(PerfumeEssentialOil::class, 'oil_category_id');
    }

    /**
     * Localized name accessor
     */
    public function getNameAttribute($value)
    {
        if (app()->getLocale() == 'en' && !empty($this->name_en)) {
            return $this->name_en;
        }
        return $value;
    }

    /**
     * Localized description accessor
     */
    public function getDescriptionAttribute($value)
    {
        if (app()->getLocale() == 'en' && !empty($this->description_en)) {
            return $this->description_en;
        }
        return $value;
    }
}
