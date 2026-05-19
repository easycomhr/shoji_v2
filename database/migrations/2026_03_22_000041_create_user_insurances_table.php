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
        // Mục đích: Lưu thông tin bảo hiểm xã hội và bảo hiểm y tế của nhân viên
        Schema::create('user_insurances', function (Blueprint $table) {
            $table->id(); // ID tự động tăng
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // ID nhân viên
            $table->string('social_insurance_number', 20)->nullable(); // Số sổ bảo hiểm xã hội
            $table->date('social_insurance_start_date')->nullable(); // Ngày bắt đầu tham gia BHXH
            $table->string('social_insurance_place', 200)->nullable(); // Nơi đăng ký BHXH
            $table->string('health_insurance_number', 20)->nullable(); // Số thẻ bảo hiểm y tế
            $table->string('health_insurance_place', 200)->nullable(); // Nơi khám chữa bệnh ban đầu (BHYT)
            $table->date('end_date')->nullable(); // Ngày hết hạn bảo hiểm
            $table->boolean('is_locked')->default(false); // Đã khóa hồ sơ bảo hiểm chưa
            $table->timestamps();

            $table->unique(['user_id']); // Mỗi nhân viên chỉ có 1 hồ sơ bảo hiểm
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_insurances');
    }
};
