<?php

namespace App\Models;

use App\Traits\UploadTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerfumeCategory extends Model
{
    use HasFactory, UploadTrait;

    protected $fillable = [
        'name',
        'name_en',
        'slug',
        'description',
        'description_en',
        'image',
        'icon',
        'parent_id',
        'type',
        'priority',
        'is_active',
        'show_in_navbar',
    ];

    /**
     * Relationship with Parent Category
     */
    public function parent()
    {
        return $this->belongsTo(PerfumeCategory::class, 'parent_id');
    }

    /**
     * Relationship with Child Categories
     */
    public function children()
    {
        return $this->hasMany(PerfumeCategory::class, 'parent_id')->orderBy('priority');
    }

    /**
     * Relationship with Products
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    /**
     * Get descriptive name based on current locale
     */
    public function getNameAttribute($value)
    {
        if (app()->getLocale() == 'en' && !empty($this->name_en)) {
            return $this->name_en;
        }
        return $value;
    }

    /**
     * Get descriptive description based on current locale
     */
    public function getDescriptionAttribute($value)
    {
        if (app()->getLocale() == 'en' && !empty($this->description_en)) {
            return $this->description_en;
        }
        return $value;
    }
}
