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
        Schema::create('admins', function (Blueprint $row) {
            $row->id();
            $row->string('name');
            $row->string('email')->unique();
            $row->string('password');
            $row->string('avatar')->nullable();
            $row->foreignId('role_id')->nullable()->constrained()->onDelete('set null');
            $row->boolean('is_blocked')->default(false);
            $row->boolean('is_notify')->default(true);
            $row->string('locale')->default('ar');
            $row->rememberToken();
            $row->timestamps();
            $row->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
