<?php

namespace App\Models;

use App\Traits\UploadTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PerfumeBrand extends Model
{
    use HasFactory, UploadTrait;

    protected $fillable = [
        'name',
        'name_en',
        'slug',
        'logo',
        'description',
        'description_en',
        'country_of_origin',
        'founded_year',
        'website',
        'is_active',
        'is_featured',
        'priority',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'founded_year' => 'integer',
        'priority' => 'integer',
    ];

    /**
     * Boot function to handle slug generation
     */
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($brand) {
            if (empty($brand->slug)) {
                $brand->slug = Str::slug($brand->name_en ?? $brand->name);
            }
        });
    }

    /**
     * Relationship with Products
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'brand_id');
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
