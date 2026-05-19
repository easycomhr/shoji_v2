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
        // Mục đích: Lưu quyền truy cập từng tài nguyên hệ thống của nhân viên
        Schema::create('user_permissions', function (Blueprint $table) {
            $table->id(); // ID tự động tăng
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // ID nhân viên
            $table->unsignedBigInteger('resource_id'); // ID tài nguyên hệ thống
            $table->boolean('can_read')->default(false); // Quyền xem
            $table->boolean('can_approve')->default(false); // Quyền phê duyệt
            $table->timestamps();

            $table->foreign('resource_id')
                ->references('id')
                ->on('application_resources')
                ->onDelete('cascade'); // Xóa resource thì xóa luôn quyền
            $table->unique(['user_id', 'resource_id']); // Mỗi nhân viên chỉ có 1 bộ quyền/resource
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_permissions');
    }
};
