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
        Schema::create('tblclients', function (Blueprint $table) {
            $table->bigIncrements('client_id');
            $table->string('firstname');
            $table->string('lastname');
            $table->string('email')->unique();
            $table->string('phno')->unique();
            $table->string('email_verified_at')->nullable();
            $table->string('password');
            $table->string('otp')->nullable();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tblclients');
    }
};
