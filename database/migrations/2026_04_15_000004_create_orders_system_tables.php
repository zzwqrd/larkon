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
        // 1. Orders table
        Schema::create('perfume_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->unsignedBigInteger('admin_id')->nullable(); // Track which admin created/managed it
            $table->string('customer_name');
            $table->string('customer_email')->nullable();
            $table->string('customer_phone');
            $table->text('shipping_address');
            $table->string('shipping_city')->nullable();
            
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('shipping_cost', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);

            $table->enum('status', ['new', 'processing', 'shipped', 'delivered', 'cancelled', 'returned'])->default('new');
            $table->string('payment_method')->nullable();
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->text('admin_note')->nullable();
            $table->timestamps();
        });

        // 2. Order Items table
        Schema::create('perfume_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('perfume_orders')->onDelete('cascade');
            $table->unsignedBigInteger('product_id')->nullable();
            // Relationship defined with the products table created earlier
            $table->foreign('product_id')->references('id')->on('products')->onDelete('set null');
            
            $table->string('product_name');
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total', 10, 2);
            $table->timestamps();
        });

        // 3. Order Status History table
        Schema::create('perfume_order_status_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('perfume_orders')->onDelete('cascade');
            $table->string('status');
            $table->text('note')->nullable();
            $table->unsignedBigInteger('changed_by')->nullable();
            $table->boolean('notify_customer')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perfume_order_status_history');
        Schema::dropIfExists('perfume_order_items');
        Schema::dropIfExists('perfume_orders');
    }
};
