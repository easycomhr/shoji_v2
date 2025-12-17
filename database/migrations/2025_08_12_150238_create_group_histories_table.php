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
        // Migration: create_group_histories_table.php
        // Mục đích: Theo dõi lịch sử thay đổi nhóm làm việc của nhân viên
        Schema::create('group_histories', function (Blueprint $table) {
            $table->id(); // ID tự động tăng
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // ID nhân viên
            $table->foreignId('group_id')->constrained(); // ID nhóm làm việc
            $table->date('from_date'); // Ngày bắt đầu ở nhóm này
            $table->date('to_date')->nullable(); // Ngày kết thúc ở nhóm này (null = hiện tại)
            $table->timestamps();

            $table->index(['user_id', 'from_date']); // Tìm lịch sử nhóm theo nhân viên
            $table->index('from_date'); // Tìm theo thời gian
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_histories');
    }
};
