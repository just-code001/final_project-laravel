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
        Schema::create('tblupcomingconcerts', function (Blueprint $table) {
            $table->id();
            $table->date('concert_date');
            $table->string('concert_singer');
            $table->string('description');
            $table->boolean('isdeleted')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tblupcomingconcerts');
    }
};
