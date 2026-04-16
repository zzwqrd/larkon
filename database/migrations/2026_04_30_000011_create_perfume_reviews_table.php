<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('perfume_reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            // Using 'products' instead of 'perfume_products' since Larkon uses 'products'
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            
            $table->unsignedBigInteger('order_id')->nullable();
            $table->foreign('order_id')->references('id')->on('perfume_orders')->onDelete('set null');
            
            $table->string('reviewer_name')->nullable();     
            $table->tinyInteger('rating');                   
            $table->text('comment')->nullable();             
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->boolean('is_verified_purchase')->default(false); 
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('perfume_reviews');
    }
};
