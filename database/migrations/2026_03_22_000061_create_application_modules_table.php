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
        // Mục đích: Lưu danh sách các module trong hệ thống ứng dụng (dùng phân quyền)
        Schema::create('application_modules', function (Blueprint $table) {
            $table->id(); // ID tự động tăng
            $table->string('module_name', 100); // Tên module hệ thống
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_modules');
    }
};
