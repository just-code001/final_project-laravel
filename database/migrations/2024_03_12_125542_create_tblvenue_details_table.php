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
        Schema::create('tblvenue_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('venue_id');
            $table->text('description')->nullable();
            $table->string('city', 255);
            $table->string('state', 255);
            $table->string('pincode', 255);
            $table->string('location', 255);
            $table->string('contact', 20);
            $table->text('food_facility')->nullable();
            $table->text('special_facility')->nullable();

            $table->foreign('venue_id')->references('id')->on('tblvenues')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tblvenue_details');
    }
};
