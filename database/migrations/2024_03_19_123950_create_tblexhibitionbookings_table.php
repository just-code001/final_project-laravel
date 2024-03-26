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
        Schema::create('tblexhibitionbookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('exhibition_id');
            $table->unsignedBigInteger('client_id');
            $table->string('client_name', 255);
            $table->string('client_email', 255);
            $table->string('contact_number', 20);
            $table->string('exhibition_name', 255);
            $table->integer('no_of_tickets');
            $table->date('booking_date');
            $table->string('exhibition_type', 50);
            $table->decimal('price', 10, 2);
            $table->string('payment_id')->nullable(); // Add order_id column after price
            $table->string('razorpay_id')->nullable();
            $table->string('payment_status')->default('pending');

            // Define foreign key constraints
            $table->foreign('exhibition_id')->references('id')->on('tblexihibitions')->onDelete('cascade');
            $table->foreign('client_id')->references('client_id')->on('tblclients')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tblexhibitionbookings');
    }
};
