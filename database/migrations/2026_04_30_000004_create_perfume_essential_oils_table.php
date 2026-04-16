<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('perfume_essential_oils', function (Blueprint $table) {
            $table->id();
            $table->foreignId('oil_category_id')->nullable()->constrained('perfume_oil_categories')->nullOnDelete();
            $table->string('name');                          
            $table->string('name_en')->nullable();           
            $table->string('origin')->nullable();            
            $table->text('description')->nullable();         
            $table->text('description_en')->nullable();
            $table->string('image')->nullable();             
            $table->decimal('price_per_unit', 10, 2)->default(0); 
            $table->string('unit')->default('ml');           
            $table->integer('stock_quantity')->default(0);
            $table->boolean('is_active')->default(true);
            $table->integer('priority')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perfume_essential_oils');
    }
};
