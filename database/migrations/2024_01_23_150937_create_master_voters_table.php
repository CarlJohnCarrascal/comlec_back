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
        Schema::create('master_voters', function (Blueprint $table) {
            $table->id();
            $table->integer('house_id');
            $table->integer('house_number');
            $table->integer('precinct_number')->nullable();
            $table->integer('purok')->nullable();
            $table->string('street')->nullable();
            $table->string('barangay');
            $table->string('municipality');
            $table->string('city');
            $table->string('fname');
            $table->string('mname')->nullable();
            $table->string('lname');
            $table->string('suffix')->nullable();
            $table->string('gender')->nullable();
            $table->date('birthdate')->nullable();
            $table->string('mark')->nullable();
            $table->string('status')->nullable();
            $table->string('ishead')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_voters');
    }
};
