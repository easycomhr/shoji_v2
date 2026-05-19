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
        // Mục đích: Lưu danh sách tài nguyên/chức năng trong từng module (dùng phân quyền)
        Schema::create('application_resources', function (Blueprint $table) {
            $table->id(); // ID tự động tăng
            $table->string('resource_name', 100); // Tên tài nguyên/chức năng
            $table->unsignedBigInteger('application_module_id')->nullable(); // ID module (nullable)
            $table->timestamps();

            $table->foreign('application_module_id')
                ->references('id')
                ->on('application_modules')
                ->nullOnDelete(); // Xóa module thì resource không bị xóa theo
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_resources');
    }
};
