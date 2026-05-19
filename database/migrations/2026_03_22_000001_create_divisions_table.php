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
        // Mục đích: Lưu thông tin các phòng ban cấp cao (division) trong công ty
        Schema::create('divisions', function (Blueprint $table) {
            $table->id(); // ID tự động tăng
            $table->string('division_name', 100); // Tên phòng ban cấp cao
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('divisions');
    }
};
