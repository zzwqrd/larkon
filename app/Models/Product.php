<?php

namespace App\Models;

use App\Traits\UploadTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory, UploadTrait;

    protected $fillable = [
        'name',
        'name_en',
        'slug',
        'description',
        'description_en',
        'image',
        'size',
        'price',
        'stock',
        'sold',
        'category_id',
        'rating',
        'reviews_count',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'rating' => 'float',
        'is_active' => 'boolean',
    ];

    /**
     * Relationship with Category
     */
    public function category()
    {
        return $this->belongsTo(PerfumeCategory::class, 'category_id');
    }

    /**
     * Relationship with Activity Logs
     */
    public function activityLogs()
    {
        return $this->morphMany(ActivityLog::class, 'userable');
    }

    /**
     * Get name based on current locale
     */
    public function getNameAttribute($value)
    {
        if (app()->getLocale() == 'en' && !empty($this->name_en)) {
            return $this->name_en;
        }
        return $value;
    }

    /**
     * Get description based on current locale
     */
    public function getDescriptionAttribute($value)
    {
        if (app()->getLocale() == 'en' && !empty($this->description_en)) {
            return $this->description_en;
        }
        return $value;
    }
}
