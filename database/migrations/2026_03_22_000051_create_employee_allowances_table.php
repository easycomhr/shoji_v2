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
        // Mục đích: Lưu các khoản phụ cấp được gán cho từng nhân viên theo thời gian
        Schema::create('employee_allowances', function (Blueprint $table) {
            $table->id(); // ID tự động tăng
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // ID nhân viên
            $table->foreignId('allowance_type_id')->constrained()->onDelete('cascade'); // ID loại phụ cấp
            $table->decimal('amount', 15, 2)->default(0); // Số tiền phụ cấp
            $table->date('start_date'); // Ngày bắt đầu áp dụng phụ cấp
            $table->date('end_date')->nullable(); // Ngày kết thúc (null = còn hiệu lực)
            $table->timestamps();

            $table->index(['user_id', 'allowance_type_id']); // Tìm phụ cấp theo nhân viên và loại
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_allowances');
    }
};
