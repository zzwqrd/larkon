<?php

namespace App\Models;

use App\Traits\UploadTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerfumeFormulaIngredient extends Model
{
    use HasFactory, UploadTrait;

    protected $fillable = [
        'formula_id',
        'essential_oil_id',
        'ingredient_name',
        'image',
        'percentage',
        'note_type',
        'note',
        'priority',
    ];

    protected $casts = [
        'percentage' => 'decimal:2',
        'priority' => 'integer',
    ];

    public function formula()
    {
        return $this->belongsTo(PerfumeFormula::class, 'formula_id');
    }

    public function essentialOil()
    {
        return $this->belongsTo(PerfumeEssentialOil::class, 'essential_oil_id');
    }

    public function getDisplayNameAttribute()
    {
        return $this->essential_oil_id && $this->essentialOil
            ? $this->essentialOil->name
            : $this->ingredient_name;
    }
}
