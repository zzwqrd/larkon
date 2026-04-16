<?php

namespace App\Models;

use App\Traits\UploadTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerfumeNote extends Model
{
    use HasFactory, UploadTrait;

    protected $table = 'perfume_notes';

    protected $fillable = [
        'name',
        'name_en',
        'description',
        'description_en',
        'image',
        'essential_oil_id',
        'oil_category_id',
        'priority',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'priority' => 'integer',
    ];

    public function essentialOil()
    {
        return $this->belongsTo(PerfumeEssentialOil::class, 'essential_oil_id');
    }

    public function oilCategory()
    {
        return $this->belongsTo(PerfumeOilCategory::class, 'oil_category_id');
    }

    public function getNameAttribute($value)
    {
        if (app()->getLocale() == 'en' && !empty($this->name_en)) {
            return $this->name_en;
        }
        return $value;
    }

    public function getDescriptionAttribute($value)
    {
        if (app()->getLocale() == 'en' && !empty($this->description_en)) {
            return $this->description_en;
        }
        return $value;
    }
}
