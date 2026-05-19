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
        // Mục đích: Lưu lịch sử thay đổi trạng thái nhân sự của nhân viên (VD: thử việc → chính thức → nghỉ việc)
        Schema::create('user_status_histories', function (Blueprint $table) {
            $table->id(); // ID tự động tăng
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // ID nhân viên
            $table->foreignId('user_status_id')->constrained()->onDelete('cascade'); // ID trạng thái nhân sự
            $table->date('start_date'); // Ngày bắt đầu áp dụng trạng thái
            $table->date('end_date')->nullable(); // Ngày kết thúc trạng thái (null = hiện tại)
            $table->text('note')->nullable(); // Ghi chú lý do thay đổi
            $table->timestamps();

            $table->index(['user_id']); // Tìm lịch sử trạng thái theo nhân viên
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_status_histories');
    }
};
