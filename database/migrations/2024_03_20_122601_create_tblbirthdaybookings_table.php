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
        Schema::create('tblbirthdaybookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->string('name', 100);
            $table->string('email', 100);
            $table->string('address', 255);
            $table->string('contact_number', 20);
            $table->string('city', 100);
            $table->string('guest_list', 255);
            $table->string('package_name', 255);
            $table->string('theme', 255);

            $table->foreign('client_id')->references('client_id')->on('tblclients')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tblbirthdaybookings');
    }
};
