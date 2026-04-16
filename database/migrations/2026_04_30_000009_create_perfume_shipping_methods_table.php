<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('perfume_shipping_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');                          
            $table->string('name_en')->nullable();
            $table->string('logo')->nullable();              
            $table->decimal('base_cost', 10, 2)->default(0); 
            $table->decimal('free_shipping_above', 10, 2)->nullable(); 
            $table->integer('estimated_days')->nullable();   
            $table->enum('type', ['local', 'international', 'both'])->default('local');
            $table->boolean('is_active')->default(true);
            $table->integer('priority')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('perfume_shipping_methods');
    }
};
