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
        Schema::create('tblusers', function (Blueprint $table) {
            $table->bigIncrements('user_id');
            $table->string('name');
            $table->string('email')->unique();
            $table->enum('type', ['staff', 'employee', 'admin']);
            $table->string('password');
            $table->string('adhaarcard_details')->nullable();
            $table->string('skills')->nullable();
            $table->decimal('salary', 10, 2);
            $table->string('status', 20);
            $table->boolean('isdeleted')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tblusers');
    }
};
