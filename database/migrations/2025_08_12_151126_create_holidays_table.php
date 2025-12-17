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
        // Mục đích: Quản lý các ngày nghỉ lễ, tết của công ty để tính lương chính xác
        Schema::create('holidays', function (Blueprint $table) {
            $table->id(); // ID tự động tăng
            $table->string('name'); // Tên ngày lễ, VD: "Tết Nguyên Đán", "Quốc Khánh", "Giáng Sinh"
            $table->date('holiday_date'); // Ngày nghỉ lễ cụ thể
            $table->integer('year'); // Năm của ngày lễ (để dễ quản lý và tìm kiếm)
            $table->boolean('is_active')->default(true); // Có áp dụng nghỉ lễ này không (có thể hủy bỏ)
            $table->timestamps();

            $table->index(['holiday_date', 'year']); // Tìm ngày lễ theo ngày và năm
            $table->unique('holiday_date'); // Mỗi ngày chỉ có 1 ngày lễ
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('holidays');
    }
};
