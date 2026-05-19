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
        // Mục đích: Lưu nhật ký chấm công từ máy chấm công (time recorder)
        Schema::create('time_recorder_logs', function (Blueprint $table) {
            $table->id(); // ID tự động tăng
            $table->date('record_date'); // Ngày chấm công
            $table->string('employee_card_id', 20); // Mã thẻ nhân viên trên máy chấm công
            $table->tinyInteger('direction'); // Hướng: 0 = vào, 1 = ra
            $table->dateTime('recorded_at'); // Thời điểm chấm công thực tế
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // ID nhân viên (nullable nếu chưa map)
            $table->timestamps();

            $table->index(['record_date']); // Tìm kiếm theo ngày
            $table->index(['employee_card_id']); // Tìm kiếm theo mã thẻ
            $table->index(['user_id']); // Tìm kiếm theo nhân viên
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('time_recorder_logs');
    }
};
