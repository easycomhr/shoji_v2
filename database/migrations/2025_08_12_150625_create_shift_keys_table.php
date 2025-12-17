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
        // Mục đích: Lưu lịch ca làm việc của từng nhân viên theo tháng (31 ngày)
        Schema::create('shift_keys', function (Blueprint $table) {
            $table->id(); // ID tự động tăng
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // ID nhân viên
            $table->integer('month'); // Tháng (1-12)
            $table->integer('year'); // Năm

            // 31 cột cho 31 ngày trong tháng (d01, d02, ..., d31)
            // Mỗi cột lưu ID của work_shift tương ứng với ngày đó
            for ($i = 1; $i <= 31; $i++) {
                $day = str_pad($i, 2, '0', STR_PAD_LEFT);
                $table->foreignId("d{$day}")->nullable()->constrained('work_shifts'); // Ca làm việc ngày $i
            }

            $table->timestamps();

            $table->unique(['user_id', 'month', 'year']); // Mỗi nhân viên chỉ có 1 lịch/tháng
            $table->index(['month', 'year']); // Tìm kiếm theo tháng/năm
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shift_keys');
    }
};
