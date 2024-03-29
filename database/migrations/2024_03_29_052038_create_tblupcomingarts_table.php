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
        Schema::create('tblupcomingarts', function (Blueprint $table) {
            $table->id();
            $table->string('art_name');
            $table->string('art_image');
            $table->date('art_date');
            $table->string('art_description');
            $table->boolean('isdeleted')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tblupcomingarts');
    }
};
