<?php

namespace App\Models;

use App\Traits\UploadTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerfumeEssentialOil extends Model
{
    use HasFactory, UploadTrait;

    protected $table = 'perfume_essential_oils';

    protected $fillable = [
        'oil_category_id',
        'name',
        'name_en',
        'origin',
        'description',
        'description_en',
        'image',
        'price_per_unit',
        'unit',
        'stock_quantity',
        'is_active',
        'priority',
    ];

    protected $casts = [
        'price_per_unit' => 'decimal:2',
        'stock_quantity' => 'integer',
        'is_active' => 'boolean',
        'priority' => 'integer',
    ];

    /**
     * Relationship with Category
     */
    public function category()
    {
        return $this->belongsTo(PerfumeOilCategory::class, 'oil_category_id');
    }

    /**
     * Relationship with Formulas
     */
    public function formulas()
    {
        return $this->belongsToMany(PerfumeFormula::class, 'perfume_formula_ingredients', 'essential_oil_id', 'formula_id')
                    ->withPivot('quantity', 'unit');
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
