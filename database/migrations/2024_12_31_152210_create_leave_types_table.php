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
        Schema::create('leave_types', function (Blueprint $table) {
            $table->id();
            $table->integer("company_id")->nullable()->comment("companies::id")->index();
            $table->integer("leave_category_id")->nullable()->comment("leave_categories::id")->index();
            $table->string("code", 50)->nullable()->comment("Mã code")->index();
            $table->string("name")->nullable()->comment("Tên")->index();
            $table->integer("kind")->nullable()->comment("Loại")->index();
            $table->double("paid_rate")->nullable()->default(0)->index();
            $table->string("note")->nullable()->comment("Ghi chú");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_types');
    }
};
