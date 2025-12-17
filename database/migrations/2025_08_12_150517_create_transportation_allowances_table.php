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
        // Mục đích: Định nghĩa các mức phụ cấp đi lại theo từng thời điểm
        Schema::create('transportation_allowances', function (Blueprint $table) {
            $table->id(); // ID tự động tăng
            $table->string('name'); // Tên loại phụ cấp, VD: "Xe máy", "Xe bus", "Ô tô"
            $table->decimal('allowance', 10, 2); // Số tiền phụ cấp (VD: 500,000 VND/tháng)
            $table->date('applied_date'); // Ngày áp dụng mức phụ cấp này
            $table->timestamps();

            $table->index('applied_date'); // Tìm phụ cấp theo thời gian áp dụng
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transportation_allowances');
    }
};
