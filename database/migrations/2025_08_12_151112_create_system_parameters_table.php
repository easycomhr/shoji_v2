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
        // Mục đích: Lưu các tham số cấu hình hệ thống có thể thay đổi mà không cần code
        Schema::create('system_parameters', function (Blueprint $table) {
            $table->id(); // ID tự động tăng
            $table->string('key')->unique(); // Tên tham số, VD: "DAY_PER_MONTH_TO_CALC_WORK_OFF", "MIN_INSURANCE_SALARY"
            $table->text('value'); // Giá trị tham số (có thể là số, chuỗi, JSON...)
            $table->string('description')->nullable(); // Mô tả ý nghĩa của tham số
            $table->string('data_type')->default('string'); // Kiểu dữ liệu: string, integer, float, boolean
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_parameters');
    }
};
