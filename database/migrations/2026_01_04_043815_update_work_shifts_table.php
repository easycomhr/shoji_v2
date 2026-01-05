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
        Schema::table('work_shifts', function (Blueprint $table) {
            // 1. Xóa 2 cột cũ
            // Kiểm tra tồn tại trước khi xóa để tránh lỗi
            if (Schema::hasColumn('work_shifts', 'ot_early')) {
                $table->dropColumn(['ot_early', 'ot_late']);
            }

            // 2. Thêm cột name sau cột code
            if (!Schema::hasColumn('work_shifts', 'name')) {
                $table->string('name', 255)->nullable()
                    ->comment('Tên ca làm việc')
                    ->after('code');
            }

            // 3. Thêm cột overtime_type_id
            if (!Schema::hasColumn('work_shifts', 'overtime_type_id')) {
                $table->bigInteger('overtime_type_id')->nullable()->default(null)
                    ->comment('Loại tăng ca mặc định')
                    ->index()
                    ->after('is_night_shift');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_shifts', function (Blueprint $table) {
            // Xóa các cột mới
            $table->dropColumn(['name', 'overtime_type_id']);

            // Khôi phục cột cũ
            $table->integer("ot_early")->nullable()->comment("Tang ca som")->index();
            $table->integer("ot_late")->nullable()->comment("Tang ca muon")->index();
        });
    }
};