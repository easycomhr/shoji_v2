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
        // Mục đích: Lưu lịch phân công ca làm việc của từng nhân viên theo ngày
        Schema::create('shift_schedules', function (Blueprint $table) {
            $table->id(); // ID tự động tăng
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // ID nhân viên
            $table->foreignId('shift_id')->constrained('work_shifts')->onDelete('cascade'); // ID ca làm việc
            $table->date('schedule_date'); // Ngày làm việc theo ca
            $table->unsignedBigInteger('department_id')->nullable(); // ID phòng ban (nullable)
            $table->timestamps();

            $table->foreign('department_id')
                ->references('id')
                ->on('departments')
                ->nullOnDelete(); // Xóa phòng ban thì schedule vẫn giữ

            $table->index(['user_id', 'schedule_date']); // Tìm lịch ca theo nhân viên và ngày
            $table->index(['department_id', 'schedule_date']); // Tìm lịch ca theo phòng ban và ngày
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shift_schedules');
    }
};
