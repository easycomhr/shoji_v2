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
        // Mục đích: Lưu cấu hình hệ thống theo từng công ty (key-value config)
        Schema::create('system_configs', function (Blueprint $table) {
            $table->id(); // ID tự động tăng
            $table->string('config_key', 100); // Tên khóa cấu hình
            $table->text('config_value'); // Giá trị cấu hình
            $table->foreignId('company_id')->constrained()->onDelete('cascade'); // ID công ty
            $table->timestamps();

            $table->unique(['config_key', 'company_id']); // Mỗi công ty chỉ có 1 giá trị/khóa
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_configs');
    }
};
