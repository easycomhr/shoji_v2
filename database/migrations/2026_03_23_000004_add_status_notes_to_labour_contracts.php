<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('labour_contracts', function (Blueprint $table) {
            $table->string('status', 20)->default('active')->after('signed_date');
            $table->text('notes')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('labour_contracts', function (Blueprint $table) {
            $table->dropColumn(['status', 'notes']);
        });
    }
};
