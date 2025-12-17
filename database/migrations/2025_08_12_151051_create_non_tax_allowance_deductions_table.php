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
        // Mục đích: Lưu các khoản phụ cấp và khấu trừ KHÔNG tính vào thu nhập chịu thuế
        Schema::create('non_tax_allowance_deductions', function (Blueprint $table) {
            $table->id(); // ID tự động tăng
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // ID nhân viên
            $table->integer('month'); // Tháng áp dụng (1-12)
            $table->integer('year'); // Năm áp dụng
            $table->decimal('allowance', 12, 2)->default(0); // Phụ cấp không chịu thuế (xăng xe, điện thoại, ăn trưa...)
            $table->decimal('deduction', 12, 2)->default(0); // Khấu trừ không chịu thuế
            $table->timestamps();

            $table->unique(['user_id', 'month', 'year']); // Mỗi nhân viên chỉ có 1 bản ghi/tháng
            $table->index(['month', 'year']); // Tìm theo tháng/năm
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('non_tax_allowance_deductions');
    }
};
