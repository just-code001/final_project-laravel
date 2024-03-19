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
        Schema::create('tblpackages', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('packagename');
            $table->string('packageimage');
            $table->decimal('packagepricing', 8, 2);
            $table->text('description');
            $table->boolean('isdeleted')->default(false);
           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tblpackages');
    }
};
