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
        Schema::table('system_parameters', function (Blueprint $table) {
            $table->bigInteger('company_id')->nullable()->after('id');
            $table->date('apply_date')->nullable()->after('value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('system_parameters', function (Blueprint $table) {
            $table->dropColumn(['company_id', 'apply_date']);
        });
    }
};
