<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PerfumeEssentialOil;
use App\Models\PerfumeOilCategory;
use App\Models\PerfumeSize;
use App\Models\PerfumeNote;
use App\Models\PerfumeShippingMethod;

class PerfumeModuleSeeder extends Seeder
{
    public function run()
    {
        // 1. Oil Categories
        $citrus = PerfumeOilCategory::firstOrCreate(['name' => 'Citrus', 'name_en' => 'Citrus']);
        $floral = PerfumeOilCategory::firstOrCreate(['name' => 'Floral', 'name_en' => 'Floral']);
        $woody = PerfumeOilCategory::firstOrCreate(['name' => 'Woody', 'name_en' => 'Woody']);

        // 2. Essential Oils
        $lemonOil = PerfumeEssentialOil::firstOrCreate(
            ['name' => 'Lemon Extract'],
            ['name_en' => 'Lemon Extract', 'oil_category_id' => $citrus->id, 'price_per_unit' => 0.5]
        );
        $roseOil = PerfumeEssentialOil::firstOrCreate(
            ['name' => 'Rose Damascena'],
            ['name_en' => 'Rose Damascena', 'oil_category_id' => $floral->id, 'price_per_unit' => 2.5]
        );
        $sandalwoodOil = PerfumeEssentialOil::firstOrCreate(
            ['name' => 'Sandalwood'],
            ['name_en' => 'Sandalwood', 'oil_category_id' => $woody->id, 'price_per_unit' => 3.0]
        );

        // 3. Sizes
        PerfumeSize::firstOrCreate(['volume_ml' => 50], ['name' => '50 ml', 'name_en' => '50 ml']);
        PerfumeSize::firstOrCreate(['volume_ml' => 100], ['name' => '100 ml', 'name_en' => '100 ml']);
        PerfumeSize::firstOrCreate(['volume_ml' => 200], ['name' => '200 ml', 'name_en' => '200 ml']);

        // 4. Notes
        PerfumeNote::firstOrCreate(
            ['name' => 'Fresh Lemon'],
            ['name_en' => 'Fresh Lemon', 'essential_oil_id' => $lemonOil->id, 'oil_category_id' => $citrus->id, 'description' => 'A bright top note.']
        );
        PerfumeNote::firstOrCreate(
            ['name' => 'Deep Wood'],
            ['name_en' => 'Deep Wood', 'essential_oil_id' => $sandalwoodOil->id, 'oil_category_id' => $woody->id, 'description' => 'A lasting base note.']
        );

        // 5. Shipping Methods
        PerfumeShippingMethod::firstOrCreate(
            ['name' => 'Aramex Local'],
            ['name_en' => 'Aramex Local', 'base_cost' => 30.00, 'free_shipping_above' => 500, 'type' => 'local']
        );
        PerfumeShippingMethod::firstOrCreate(
            ['name' => 'DHL International'],
            ['name_en' => 'DHL International', 'base_cost' => 150.00, 'type' => 'international']
        );
    }
}
