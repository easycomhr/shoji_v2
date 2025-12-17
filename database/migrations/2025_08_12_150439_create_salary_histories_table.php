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
        // Mục đích: Lưu lịch sử thay đổi lương và các khoản phụ cấp của nhân viên theo thời gian
        Schema::create('salary_histories', function (Blueprint $table) {
            $table->id(); // ID tự động tăng
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // ID nhân viên (xóa cascade)
            $table->date('applied_date'); // Ngày áp dụng mức lương mới
            $table->decimal('salary', 12, 2); // Lương cơ bản (VD: 10,000,000 VND)
            $table->decimal('position_allowance', 12, 2)->default(0); // Phụ cấp chức vụ
            $table->decimal('evaluation_allowance', 12, 2)->default(0); // Phụ cấp đánh giá hiệu suất
            $table->decimal('harmfulness_allowance', 12, 2)->default(0); // Phụ cấp độc hại (môi trường nguy hiểm)
            $table->decimal('skill_allowance', 12, 2)->default(0); // Phụ cấp kỹ năng/chuyên môn
            $table->decimal('seniority_allowance', 12, 2)->default(0); // Phụ cấp thâm niên
            $table->decimal('adjustment_allowance', 12, 2)->default(0); // Phụ cấp điều chỉnh/đặc biệt
            $table->decimal('slippage_allowance', 12, 2)->default(0); // Phụ cấp chăm sóc con nhỏ
            $table->decimal('regular_allowance', 12, 2)->default(0); // Phụ cấp thường xuyên khác
            $table->decimal('union_fee', 12, 2)->default(0); // Phí công đoàn
            $table->boolean('health_insurance_included')->default(true); // Có tham gia BHYT không
            $table->boolean('social_insurance_included')->default(true); // Có tham gia BHXH không
            $table->timestamps();

            $table->index(['user_id', 'applied_date']); // Tìm kiếm theo nhân viên và thời gian
            $table->unique(['user_id', 'applied_date']); // Mỗi nhân viên chỉ có 1 mức lương/ngày
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_histories');
    }
};
