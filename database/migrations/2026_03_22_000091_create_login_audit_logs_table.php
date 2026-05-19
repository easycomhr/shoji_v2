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
        // Mục đích: Ghi nhật ký đăng nhập hệ thống của từng nhân viên (audit trail)
        Schema::create('login_audit_logs', function (Blueprint $table) {
            $table->id(); // ID tự động tăng
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // ID nhân viên
            $table->timestamp('logged_in_at'); // Thời điểm đăng nhập
            $table->string('ip_address', 45); // Địa chỉ IP đăng nhập (hỗ trợ IPv6)
            $table->timestamps();

            $table->index(['user_id']); // Tìm lịch sử đăng nhập theo nhân viên
            $table->index(['logged_in_at']); // Tìm kiếm theo thời gian đăng nhập
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('login_audit_logs');
    }
};
