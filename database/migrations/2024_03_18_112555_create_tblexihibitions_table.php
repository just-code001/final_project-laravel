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
        Schema::create('tblexihibitions', function (Blueprint $table) {
            $table->id();
            $table->string('event_name');
            $table->string('type');
            $table->string('exhibition_image')->nullable();
            $table->decimal('event_pricing', 10, 2)->nullable();
            $table->date('event_starting_date');
            $table->date('event_ending_date');
            $table->string('location');
            $table->string('city');
            $table->string('state');
            $table->boolean('isdeleted')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tblexihibitions');
    }
};
