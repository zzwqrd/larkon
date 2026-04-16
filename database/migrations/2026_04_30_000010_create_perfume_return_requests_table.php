<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('perfume_return_requests', function (Blueprint $table) {
            $table->id();
            // Note: Larkon might use `orders` instead of `perfume_orders`
            $table->unsignedBigInteger('order_id');
            // Assuming the order table is `perfume_orders` based on reference, but Larkon context might require just `orders`. 
            // In Phase 5 'perfume_orders' was migrated. I will use 'perfume_orders' to be safe.
            $table->foreign('order_id')->references('id')->on('perfume_orders')->onDelete('cascade');
            
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            
            $table->string('return_number')->unique();       
            $table->text('items_to_return');                 
            $table->enum('reason', ['defective', 'wrong_item', 'not_as_described', 'changed_mind', 'other'])->default('other');
            $table->text('reason_details')->nullable();      
            $table->string('image_1')->nullable();           
            $table->string('image_2')->nullable();
            $table->string('image_3')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'refunded', 'exchanged'])->default('pending');
            $table->text('admin_note')->nullable();          
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('perfume_return_requests');
    }
};
