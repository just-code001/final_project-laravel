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
        Schema::table('tblbirthdaybookings', function (Blueprint $table) {
            $table->date('birthday_date')->after('contact_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tblbirthdaybookings', function (Blueprint $table) {
            $table->dropColumn('birthday_date');
        });
    }
};
