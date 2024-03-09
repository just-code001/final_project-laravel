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
        Schema::create('tblcontact_uses', function (Blueprint $table) {
            $table->bigIncrements('contact_id');
            $table->string('sender_name', 255);
            $table->string('sender_email', 255);
            $table->text('message');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tblcontact_uses');
    }
};
