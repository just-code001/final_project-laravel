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
        Schema::create('tblconcerts', function (Blueprint $table) {
            $table->id();
            $table->string('event_name', 255);
            $table->string('singer', 255);
            $table->string('event_timing', 255);
            $table->date('concert_date');
            $table->string('city', 255);
            $table->string('state', 255);
            $table->string('pincode', 10);
            $table->string('location', 255);
            $table->string('concert_image', 255)->nullable();
            $table->text('description')->nullable();
            $table->string('ticket_type1', 50);
            $table->decimal('ticket_pricing1', 10, 2);
            $table->string('ticket_type2', 50)->nullable();
            $table->decimal('ticket_pricing2', 10, 2)->nullable();
            $table->string('ticket_type3', 50)->nullable();
            $table->decimal('ticket_pricing3', 10, 2)->nullable();
            $table->boolean('isdeleted')->default(false); // Adding isdeleted column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tblconcerts');
    }
};
