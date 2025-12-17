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
        // Mục đích: Lưu thông tin giảm trừ gia cảnh cho tính thuế TNCN
        Schema::create('user_personal_income_tax_deductions', function (Blueprint $table) {
            $table->id(); // ID tự động tăng
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // ID nhân viên
            $table->date('from_date'); // Ngày áp dụng giảm trừ này
            $table->integer('number_of_dependents')->default(0); // Số người phụ thuộc (vợ/chồng, con, bố mẹ...)
            $table->timestamps();

            $table->index(['user_id', 'from_date']); // Tìm theo nhân viên và thời gian
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_personal_income_tax_deductions');
    }
};
