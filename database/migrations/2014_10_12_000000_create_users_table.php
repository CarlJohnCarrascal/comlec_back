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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('type')->nullable();
            $table->string('corvered_area_country')->nullable();
            $table->string('corvered_area_city')->nullable();
            $table->string('corvered_area_district')->nullable();
            $table->string('corvered_area_municipality')->nullable();
            $table->string('corvered_area_barangay')->nullable();
            $table->string('alias')->default('Me')->nullable();
            $table->string('color')->default('#05b828');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
