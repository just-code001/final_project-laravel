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
        Schema::table('tblvenue_bookings', function (Blueprint $table) {
            $table->string('payment_id')->nullable()->after('price'); // Add order_id column after price
            $table->string('razorpay_id')->nullable()->after('payment_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tblvenue_bookings', function (Blueprint $table) {
            $table->dropColumn('payment_id');
            $table->dropColumn('razorpay_id');
        });
    }
};
