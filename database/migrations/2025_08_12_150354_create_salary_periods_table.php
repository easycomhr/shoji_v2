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
        // Mục đích: Định nghĩa các kỳ tính lương (tháng/quý) và thông tin liên quan
        Schema::create('salary_periods', function (Blueprint $table) {
            $table->id(); // ID tự động tăng
            $table->string('name'); // Tên kỳ lương, VD: "Tháng 1/2024", "Q1 2024"
            $table->date('from_date'); // Ngày bắt đầu kỳ lương
            $table->date('to_date'); // Ngày kết thúc kỳ lương
            $table->integer('standard_working_days'); // Số ngày làm việc chuẩn trong kỳ (VD: 22 ngày)
            $table->boolean('is_locked')->default(false); // Trạng thái khóa kỳ lương (không cho sửa)
            $table->timestamps();

            $table->index(['from_date', 'to_date']); // Tìm kiếm theo khoảng thời gian
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_periods');
    }
};
