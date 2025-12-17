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
        Schema::create('work_shifts', function (Blueprint $table) {
            $table->id();
            $table->integer("company_id")->nullable()->comment("companies::id")->index();
            $table->string("code", 50)->nullable()->comment("Mã code")->index();
            $table->time("work_start")->nullable()->comment("Băt đầu ca")->index();
            $table->time("work_end")->nullable()->comment("Ket thuc ca")->index();
            $table->integer("is_day_off")->nullable()->default(0)->comment("Ca ngay nghi | 1: Yes | 0: No")->index();
            $table->integer("is_night_shift")->nullable()->default(0)->comment("Ca dem | 1: Yes | 0: No")->index();
            $table->integer("ot_early")->nullable()->comment("Tang ca som")->index();
            $table->integer("ot_late")->nullable()->comment("Tang ca muon")->index();
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
        Schema::dropIfExists('work_shifts');
    }
};
