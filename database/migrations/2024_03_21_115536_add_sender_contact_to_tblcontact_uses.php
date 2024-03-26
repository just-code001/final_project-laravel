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
        Schema::table('tblcontact_uses', function (Blueprint $table) {
            $table->string('sender_contact', 255)->after('sender_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tblcontact_uses', function (Blueprint $table) {
            $table->dropColumn('sender_contact');
        });
    }
};
