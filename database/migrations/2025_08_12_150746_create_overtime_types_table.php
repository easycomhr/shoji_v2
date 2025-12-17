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
        // Mục đích: Định nghĩa các loại tăng ca và hệ số lương tương ứng
        Schema::create('overtime_types', function (Blueprint $table) {
            $table->id(); // ID tự động tăng
            $table->string('name'); // Tên loại tăng ca, VD: "Tăng ca ngày", "Tăng ca đêm", "Tăng ca chủ nhật"
            $table->string('code')->unique(); // Mã loại tăng ca, VD: "OT_DAY", "OT_NIGHT", "OT_SUNDAY"
            $table->decimal('rate_multiplier', 3, 2); // Hệ số lương tăng ca (1.5, 2.0, 3.0)
            $table->decimal('non_tax_multiplier', 3, 2)->default(0.5); // Phần không chịu thuế (50% = 0.5)
            $table->boolean('is_active')->default(true); // Trạng thái hoạt động
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('overtime_types');
    }
};
