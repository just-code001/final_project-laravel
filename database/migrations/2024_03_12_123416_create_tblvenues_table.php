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
        Schema::create('tblvenues', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('venue_category');
            $table->string('venue_image')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('rating', 3, 2);
            $table->string('venue_capacity');
            $table->string('status', 20);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tblvenues');
    }
};
