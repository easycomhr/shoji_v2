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
        // Mục đích: Lưu số ngày phép tích lũy (chưa sử dụng) của từng nhân viên theo năm
        Schema::create('accumulation_leaves', function (Blueprint $table) {
            $table->id(); // ID tự động tăng
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // ID nhân viên
            $table->year('of_year'); // Năm tích lũy
            $table->decimal('accumulated_days', 5, 2)->default(0); // Số ngày phép tích lũy
            $table->timestamps();

            $table->unique(['user_id', 'of_year']); // Mỗi nhân viên chỉ có 1 bản ghi tích lũy/năm
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accumulation_leaves');
    }
};
