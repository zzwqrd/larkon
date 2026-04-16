<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perfume_notes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_en')->nullable();
            $table->text('description')->nullable();
            $table->text('description_en')->nullable();
            $table->string('image')->nullable();

            $table->unsignedBigInteger('essential_oil_id')->nullable();
            $table->foreign('essential_oil_id')
                ->references('id')
                ->on('perfume_essential_oils')
                ->onDelete('set null');

            $table->unsignedBigInteger('oil_category_id')->nullable();
            $table->foreign('oil_category_id')
                ->references('id')
                ->on('perfume_oil_categories')
                ->onDelete('set null');

            $table->integer('priority')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perfume_notes');
    }
};
