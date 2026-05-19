<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('labour_contracts', function (Blueprint $table) {
            $table->string('contract_number', 50)->nullable()->after('contract_type_id');
        });
    }

    public function down(): void
    {
        Schema::table('labour_contracts', function (Blueprint $table) {
            $table->dropColumn('contract_number');
        });
    }
};
