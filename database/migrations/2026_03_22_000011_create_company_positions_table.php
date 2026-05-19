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
        // Mục đích: Lưu danh mục chức danh/vị trí công ty (dùng cho hồ sơ nhân viên)
        Schema::create('company_positions', function (Blueprint $table) {
            $table->id(); // ID tự động tăng
            $table->string('position_name', 100); // Tên chức danh
            $table->text('description')->nullable(); // Mô tả chức danh
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_positions');
    }
};
