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
        // Mục đích: Lưu thông tin tăng ca thực tế của từng nhân viên
        Schema::create('user_overtimes', function (Blueprint $table) {
            $table->id(); // ID tự động tăng
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // ID nhân viên
            $table->foreignId('overtime_type_id')->constrained(); // ID loại tăng ca
            $table->date('overtime_date'); // Ngày tăng ca
            $table->decimal('hours', 4, 2); // Số giờ tăng ca gốc
            $table->decimal('modified_hours', 4, 2)->nullable(); // Số giờ tăng ca đã điều chỉnh (nếu có)
            $table->boolean('is_approved')->default(false); // Đã được phê duyệt chưa
            $table->timestamps();

            $table->index(['user_id', 'overtime_date']); // Tìm tăng ca theo nhân viên và ngày
            $table->index(['overtime_date', 'overtime_type_id']); // Thống kê tăng ca theo loại
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_overtimes');
    }
};
