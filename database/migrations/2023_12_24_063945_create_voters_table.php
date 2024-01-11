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
        Schema::create('voters', function (Blueprint $table) {
            $table->id();
            $table->integer('house_id');
            $table->integer('house_number');
            $table->integer('purok');
            $table->string('barangay');
            $table->string('municipality');
            $table->string('city');
            $table->string('fname');
            $table->string('mname');
            $table->string('lname');
            $table->string('suffix');
            $table->string('gender');
            $table->date('birthdate');
            $table->string('mark');
            $table->string('status');
            $table->string('ishead');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voters');
    }
};
