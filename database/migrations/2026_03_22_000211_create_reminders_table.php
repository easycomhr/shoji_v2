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
        // Mục đích: Lưu nhắc nhở tự động liên quan đến nhân viên (VD: hết hạn hợp đồng, sinh nhật...)
        Schema::create('reminders', function (Blueprint $table) {
            $table->id(); // ID tự động tăng
            $table->string('reminder_type', 50); // Loại nhắc nhở (contract_expiry, birthday, insurance...)
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // ID nhân viên liên quan
            $table->date('reminder_date'); // Ngày cần nhắc nhở
            $table->text('content'); // Nội dung nhắc nhở
            $table->boolean('is_sent')->default(false); // Đã gửi thông báo chưa
            $table->timestamps();

            $table->index(['reminder_date']); // Tìm nhắc nhở theo ngày
            $table->index(['user_id']); // Tìm nhắc nhở theo nhân viên
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reminders');
    }
};
