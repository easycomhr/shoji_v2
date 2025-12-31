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
        Schema::create('contract_types', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('company_id')->nullable()->default(null);

            $table->string('code', 20)->nullable();
            $table->string('name', 255)->nullable();
            $table->string('note', 255)->nullable();

            $table->integer('created_user')->nullable();
            $table->integer('updated_user')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contract_types');
    }
};