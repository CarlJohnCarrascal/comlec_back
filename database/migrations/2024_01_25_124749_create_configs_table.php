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
        Schema::create('configs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->string('primary_color')->default('#0b08e6');
            $table->string('right_color')->default('#05b828');
            $table->string('left_color')->default('#f20f0f');
            $table->string('undecided_color')->default('#00ad9c');
            $table->string('unmarked_color')->default('#949494');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configs');
    }
};
