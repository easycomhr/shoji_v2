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
        // Migration: create_groups_table.php
        // Mục đích: Định nghĩa các nhóm/team làm việc với thông số công việc riêng
        Schema::create('groups', function (Blueprint $table) {
            $table->id(); // ID tự động tăng
            $table->string('name'); // Tên nhóm, VD: "Team A", "Nhóm Sản Xuất 1"
            $table->string('code')->unique(); // Mã nhóm, VD: "TEAM_A", "PROD_01"
            $table->integer('working_days_per_month')->default(20); // Số ngày làm việc chuẩn/tháng của nhóm
            $table->decimal('working_hours_per_day', 3, 1)->default(9.5); // Số giờ làm việc chuẩn/ngày (VD: 8.0, 9.5)
            $table->boolean('is_active')->default(true); // Trạng thái hoạt động của nhóm
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('groups');
    }
};
