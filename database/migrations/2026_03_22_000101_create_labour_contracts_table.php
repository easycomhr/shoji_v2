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
        // Mục đích: Lưu hợp đồng lao động của từng nhân viên theo từng loại hợp đồng
        Schema::create('labour_contracts', function (Blueprint $table) {
            $table->id(); // ID tự động tăng
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // ID nhân viên
            $table->foreignId('contract_type_id')->constrained()->onDelete('cascade'); // ID loại hợp đồng
            $table->date('start_date'); // Ngày bắt đầu hợp đồng
            $table->date('end_date')->nullable(); // Ngày kết thúc hợp đồng (null = vô thời hạn)
            $table->date('signed_date')->nullable(); // Ngày ký hợp đồng
            $table->string('status', 20)->default('active'); // Trạng thái hợp đồng
            $table->text('notes')->nullable(); // Ghi chú
            $table->timestamps();

            $table->index(['user_id']); // Tìm hợp đồng theo nhân viên
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('labour_contracts');
    }
};
