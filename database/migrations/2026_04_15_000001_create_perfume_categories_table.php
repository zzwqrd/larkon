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
        Schema::create('perfume_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');                          // الاسم بالعربية
            $table->string('name_en')->nullable();           // الاسم بالإنجليزية
            $table->string('slug')->unique();                // الرابط المختصر
            $table->text('description')->nullable();         // الوصف
            $table->text('description_en')->nullable();      // الوصف بالإنجليزية
            $table->string('image')->nullable();             // الصورة
            $table->string('icon')->nullable();              // الأيقونة
            $table->unsignedBigInteger('parent_id')->nullable(); // القسم الأب (للأقسام الفرعية)
            $table->foreign('parent_id')->references('id')->on('perfume_categories')->onDelete('set null');
            $table->enum('type', ['editorial', 'products'])->default('products'); // قسم مقالي أو منتجات
            $table->integer('priority')->default(0);         // الترتيب
            $table->boolean('is_active')->default(true);     // نشط / غير نشط
            $table->boolean('show_in_navbar')->default(true); // إظهار في شريط التنقل
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perfume_categories');
    }
};
