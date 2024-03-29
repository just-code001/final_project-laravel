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
        Schema::create('tblspecialservices', function (Blueprint $table) {
            $table->id();
            $table->string('service_name', 100);
            $table->string('service_image', 255)->nullable();
            $table->string('other_img1', 100)->nullable();
            $table->string('other_img2', 100)->nullable();
            $table->string('other_img3', 100)->nullable();
            $table->text('description')->nullable();
            $table->text('testimonial')->nullable();
            $table->boolean('isdeleted')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tblspecialservices');
    }
};
