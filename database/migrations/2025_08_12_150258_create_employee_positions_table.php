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
        // Migration: create_employee_positions_table.php
        // Mục đích: Lưu thông tin chức vụ/vị trí công việc của nhân viên
        Schema::create('employee_positions', function (Blueprint $table) {
            $table->id(); // ID tự động tăng
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // ID nhân viên
            $table->string('company_position_id'); // ID chức vụ theo hệ thống công ty (có thể là string)
            $table->date('from_date'); // Ngày bắt đầu giữ chức vụ này
            $table->date('to_date')->nullable(); // Ngày kết thúc chức vụ (null = hiện tại)
            $table->timestamps();

            $table->index(['user_id', 'from_date']); // Tìm chức vụ theo nhân viên và thời gian
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_positions');
    }
};
