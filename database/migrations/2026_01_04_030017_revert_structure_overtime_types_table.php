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
        Schema::table('overtime_types', function (Blueprint $table) {
            // 1. Xóa các cột hiện có (Schema cũ/hiện tại trong DB)
            $columnsToDrop = [
                'rate_multiplier',
                'non_tax_multiplier',
                'is_active'
            ];
            $table->dropColumn($columnsToDrop);

            // 2. Thêm lại các cột theo yêu cầu ban đầu
            // Lưu ý: 'name' giữ nguyên

            $table->bigInteger('company_id')->nullable()->default(null)->after('id');
            $table->time('from_time')->nullable()->after('name');
            $table->time('to_time')->nullable()->after('from_time');
//            $table->decimal('paid_rate')->nullable()->default(0)->after('to_time');
            $table->tinyInteger('status')->nullable()->default(1)->comment('0: Inactive, 1: Active')->after('paid_rate');
            $table->string('note', 255)->nullable()->after('status');

            $table->integer('created_user')->nullable();
            $table->integer('updated_user')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('overtime_types', function (Blueprint $table) {
            // Xóa các cột vừa thêm
            $table->dropColumn([
                'company_id', 'from_time', 'to_time', 'status', 'note',
                'created_user', 'updated_user', 'deleted_at'
            ]);

            // Thêm lại các cột đã xóa (để rollback)
            $table->string('code')->nullable();
            $table->decimal('rate_multiplier', 3, 2)->nullable();
            $table->decimal('non_tax_multiplier', 3, 2)->default(0.5);
            $table->boolean('is_active')->default(true);
        });
    }
};