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
        Schema::create('annual_leaves', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('company_id')->nullable()->comment('companies::id');
            $table->bigInteger('user_id')->comment('ID người dùng, tham chiếu đến bảng users (users.id)');
            $table->string('user_code')->comment('Mã nhân viên, tham chiếu hoặc đồng bộ với users.code');
            $table->integer('year')->nullable()->comment('Năm áp dụng của phép năm (ví dụ: 2025)');
            $table->double('total_leave_days')->nullable()->default(0)->comment('Tổng số ngày phép được cấp trong năm, bao gồm chuyển từ năm trước');
            $table->double('used_leave_days')->nullable()->default(0)->comment('Số ngày phép đã sử dụng trong năm');
            $table->double('remaining_leave_days')->nullable()->default(0)->comment('Số ngày phép còn lại trong năm');
            $table->double('annual_transfer')->nullable()->default(0)->comment('Số ngày phép chuyển từ năm trước');
            $table->double('transfer_used_by_march')->nullable()->default(0)->comment('Số ngày phép chuyển từ năm trước đã dùng trước ngày 31/03');
            $table->double('transfer_cleared')->nullable()->default(0)->comment('Số ngày phép chuyển từ năm trước bị xóa vào ngày 31/03');
            $table->double('total_accrued_in_year')->nullable()->default(0)->comment('Tổng số ngày phép được cấp mới trong năm, không tính chuyển từ năm trước');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('annual_leaves');
    }
};
