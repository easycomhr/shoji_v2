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
        // Mục đích: Lưu phân bổ ngày phép hàng tháng của từng nhân viên trong năm
        Schema::create('annual_leave_by_month', function (Blueprint $table) {
            $table->id(); // ID tự động tăng
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // ID nhân viên
            $table->year('of_year'); // Năm áp dụng
            $table->tinyInteger('of_month')->unsigned(); // Tháng áp dụng (1-12)
            $table->unsignedBigInteger('allowance_id')->nullable(); // ID phụ cấp liên quan (nullable)
            $table->decimal('allowance_amount', 5, 2)->default(0); // Số ngày phép được cấp trong tháng
            $table->timestamps();

            $table->unique(['user_id', 'of_year', 'of_month']); // Mỗi nhân viên chỉ có 1 bản ghi/tháng/năm
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('annual_leave_by_month');
    }
};
