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
        Schema::create('tblvenue_bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('venue_id');
            $table->unsignedBigInteger('client_id'); // Add client_id column
            $table->string('client_name', 255);
            $table->string('client_email', 255);
            $table->string('contact_number', 20);
            $table->string('venue_name', 255);
            $table->integer('no_of_guests');
            $table->date('checkin_date');
            $table->date('checkout_date');
            $table->decimal('price', 10, 2);
            $table->string('payment_status')->default('pending');
            $table->string('special_request')->nullable();

            // Define foreign key constraints
            $table->foreign('venue_id')->references('id')->on('tblvenues')->onDelete('cascade');
            $table->foreign('client_id')->references('client_id')->on('tblclients')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tblvenue_bookings');
    }
};
