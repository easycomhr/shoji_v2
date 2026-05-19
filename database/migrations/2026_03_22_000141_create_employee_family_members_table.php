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
        // Mục đích: Lưu thông tin thành viên gia đình của nhân viên (dùng cho BHYT, giảm trừ gia cảnh)
        Schema::create('employee_family_members', function (Blueprint $table) {
            $table->id(); // ID tự động tăng
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // ID nhân viên
            $table->string('last_name', 50); // Họ của thành viên gia đình
            $table->string('first_name', 50); // Tên của thành viên gia đình
            $table->string('relationship', 50); // Quan hệ với nhân viên (vợ/chồng, con, cha/mẹ...)
            $table->tinyInteger('age')->unsigned()->nullable(); // Tuổi của thành viên
            $table->string('profession', 100)->nullable(); // Nghề nghiệp
            $table->string('residence_place', 200)->nullable(); // Nơi thường trú
            $table->timestamps();

            $table->index(['user_id']); // Tìm thành viên gia đình theo nhân viên
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_family_members');
    }
};
