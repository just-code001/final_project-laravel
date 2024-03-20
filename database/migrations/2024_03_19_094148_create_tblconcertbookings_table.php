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
        Schema::create('tblconcertbookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('concert_id');
            $table->unsignedBigInteger('client_id'); // Assuming the user is booking the concert
            $table->string('client_name', 255);
            $table->string('client_email', 255);
            $table->string('contact_number', 20);
            $table->string('concert_name', 255);
            $table->integer('no_of_tickets');
            $table->date('booking_date');
            $table->string('ticket_type', 50);
            $table->decimal('price', 10, 2);
            $table->string('payment_id')->nullable(); // Add order_id column after price
            $table->string('razorpay_id')->nullable();
            $table->string('payment_status')->default('pending');

            // Define foreign key constraints
            $table->foreign('concert_id')->references('id')->on('tblconcerts')->onDelete('cascade');
            $table->foreign('client_id')->references('client_id')->on('tblclients')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tblconcertbookings');
    }
};
