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
        Schema::create('monthly_leave_balances', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('company_id')->comment('companies::id');
            $table->bigInteger('user_id')->comment('ID người dùng, tham chiếu đến bảng users (users.id)');
            $table->string('user_code')->comment('Mã nhân viên, tham chiếu hoặc đồng bộ với users.code');
            $table->integer('year')->nullable()->comment('Năm của bản ghi lũy kế (ví dụ: 2025)');
            $table->integer('month')->nullable()->comment('Tháng của bản ghi lũy kế (1-12)');
            $table->double('opening_balance')->nullable()->default(0)->comment('Số dư ngày phép đầu kỳ (đầu tháng)');
            $table->double('monthly_accrued')->nullable()->default(0)->comment('Số ngày phép được cấp thêm trong tháng');
            $table->double('monthly_used')->nullable()->default(0)->comment('Số ngày phép đã sử dụng trong tháng');
            $table->double('monthly_remaining')->nullable()->default(0)->comment('Số ngày phép còn lại cuối tháng');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_leave_balances');
    }
};
