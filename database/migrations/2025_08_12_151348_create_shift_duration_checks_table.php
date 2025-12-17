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
        // Mục đích: Lưu thông tin chấm công thực tế và tính toán đi muộn/về sớm
        Schema::create('shift_duration_checks', function (Blueprint $table) {
            $table->id(); // ID tự động tăng
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // ID nhân viên
            $table->date('io_date'); // Ngày chấm công (Input/Output date)
            $table->time('check_in')->nullable(); // Giờ chấm công vào
            $table->time('check_out')->nullable(); // Giờ chấm công ra
            $table->decimal('early_minutes', 8, 2)->default(0); // Số phút về sớm so với ca làm việc
            $table->decimal('late_minutes', 8, 2)->default(0); // Số phút đi muộn so với ca làm việc
            $table->decimal('early_modify', 8, 2)->default(0); // Số phút về sớm đã được điều chỉnh (sau khi xử lý)
            $table->timestamps();

            $table->unique(['user_id', 'io_date']); // Mỗi nhân viên chỉ có 1 bản ghi chấm công/ngày
            $table->index('io_date'); // Tìm kiếm theo ngày chấm công
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shift_duration_checks');
    }
};
