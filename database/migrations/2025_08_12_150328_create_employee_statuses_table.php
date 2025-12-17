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
        // Migration: create_employee_statuses_table.php
        // Mục đích: Theo dõi lịch sử thay đổi trạng thái làm việc của nhân viên
        Schema::create('employee_statuses', function (Blueprint $table) {
            $table->id(); // ID tự động tăng
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // ID nhân viên
            $table->integer('status_id'); // ID trạng thái (2=active, 4=resigned, 8=working_in_japan, 11=temporary, 12=probation)
            $table->date('applied_date'); // Ngày áp dụng trạng thái này
            $table->text('notes')->nullable(); // Ghi chú thêm về trạng thái
            $table->timestamps();

            $table->index(['user_id', 'applied_date']); // Tìm theo nhân viên và thời gian
            $table->index(['status_id', 'applied_date']); // Thống kê theo trạng thái
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_statuses');
    }
};
