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
        // Mục đích: Lưu bảng chấm công tổng hợp cuối kỳ của từng nhân viên (dùng tính lương)
        Schema::create('final_timesheets', function (Blueprint $table) {
            $table->id(); // ID tự động tăng
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // ID nhân viên
            $table->foreignId('salary_period_id')->constrained()->onDelete('cascade'); // ID kỳ lương
            $table->decimal('working_days', 5, 2)->default(0); // Số ngày công thực tế
            $table->decimal('paid_leave_days', 4, 1)->default(0); // Số ngày nghỉ phép có lương
            $table->decimal('unpaid_leave_days', 4, 1)->default(0); // Số ngày nghỉ không lương
            $table->integer('total_ot_minutes')->default(0); // Tổng số phút tăng ca trong kỳ
            $table->integer('late_early_minutes')->default(0); // Tổng số phút đi muộn/về sớm
            $table->timestamps();

            $table->unique(['user_id', 'salary_period_id']); // Mỗi nhân viên chỉ có 1 bảng chấm công/kỳ
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('final_timesheets');
    }
};
