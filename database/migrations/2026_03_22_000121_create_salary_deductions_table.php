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
        // Mục đích: Lưu các khoản khấu trừ lương của nhân viên theo kỳ lương
        Schema::create('salary_deductions', function (Blueprint $table) {
            $table->id(); // ID tự động tăng
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // ID nhân viên
            $table->foreignId('salary_period_id')->constrained()->onDelete('cascade'); // ID kỳ lương
            $table->decimal('deduction_amount', 15, 2)->default(0); // Số tiền khấu trừ
            $table->text('note')->nullable(); // Ghi chú lý do khấu trừ
            $table->timestamps();

            $table->index(['user_id', 'salary_period_id']); // Tìm khấu trừ theo nhân viên và kỳ lương
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_deductions');
    }
};
