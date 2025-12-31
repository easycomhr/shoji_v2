<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('nations', function (Blueprint $table) {
            $table->string('short_name', 20)->nullable()->after('name');
            $table->string('area_code', 20)->nullable()->after('short_name');
            $table->integer('created_user')->nullable();
            $table->integer('updated_user')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('nations', function (Blueprint $table) {
            $table->dropColumn(['short_name', 'area_code', 'created_user', 'updated_user']);
        });
    }
};