<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_insurances', function (Blueprint $table) {
            $table->renameColumn('end_date', 'social_insurance_end_date');
        });
    }

    public function down(): void
    {
        Schema::table('user_insurances', function (Blueprint $table) {
            $table->renameColumn('social_insurance_end_date', 'end_date');
        });
    }
};
