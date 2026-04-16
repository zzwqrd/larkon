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
        Schema::create('perfume_formula_ingredients', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('formula_id');
            $table->foreign('formula_id')->references('id')->on('perfume_formulas')->onDelete('cascade');
            $table->unsignedBigInteger('essential_oil_id')->nullable();
            $table->foreign('essential_oil_id')->references('id')->on('perfume_essential_oils')->onDelete('set null');
            $table->string('ingredient_name')->nullable();   
            $table->string('image')->nullable();             
            $table->decimal('percentage', 5, 2)->nullable(); 
            $table->string('note_type')->nullable();         // top / heart / base
            $table->text('note')->nullable();                
            $table->integer('priority')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perfume_formula_ingredients');
    }
};
