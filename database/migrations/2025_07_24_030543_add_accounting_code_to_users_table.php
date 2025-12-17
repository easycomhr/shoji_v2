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
        Schema::table('users', function (Blueprint $table) {
            $table->string('accounting_code', 50)->nullable()->after('tax_code');
            $table->date('user_status_from_date')->nullable()->after('user_status_id');
            $table->date('terminate_date')->nullable()->after('user_status_from_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['user_status_from_date', 'accounting_code']);
        });
    }
};
