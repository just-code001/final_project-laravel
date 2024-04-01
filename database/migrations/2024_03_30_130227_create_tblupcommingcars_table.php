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
        Schema::create('tblupcommingcars', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('carimage');
            $table->string('city');
            $table->time('time');
            $table->date('date');
            $table->string('description');
            $table->boolean('isdeleted')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tblupcommingcars');
    }
};
