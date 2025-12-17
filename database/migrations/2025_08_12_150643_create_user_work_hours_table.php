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
        // Mục đích: Lưu giờ làm việc thực tế của nhân viên (check-in/check-out)
        Schema::create('user_work_hours', function (Blueprint $table) {
            $table->id(); // ID tự động tăng
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // ID nhân viên
            $table->date('date_worked'); // Ngày làm việc
            $table->datetime('from_datetime'); // Thời gian bắt đầu làm việc thực tế (có ngày giờ)
            $table->datetime('to_datetime'); // Thời gian kết thúc làm việc thực tế (có ngày giờ)
            $table->time('work_start'); // Giờ bắt đầu ca theo lịch
            $table->time('work_end'); // Giờ kết thúc ca theo lịch
            $table->decimal('actual_hours', 4, 2); // Số giờ làm việc thực tế
            $table->timestamps();

            $table->unique(['user_id', 'date_worked']); // Mỗi nhân viên chỉ có 1 bản ghi/ngày
            $table->index('date_worked'); // Tìm kiếm theo ngày
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_work_hours');
    }
};
