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
        // Mục đích: Lưu bản dịch đa ngôn ngữ (Việt/Anh) cho giao diện hệ thống
        Schema::create('language_translations', function (Blueprint $table) {
            $table->id(); // ID tự động tăng
            $table->string('lang_code', 100); // Mã khóa ngôn ngữ (translation key)
            $table->text('vi_text'); // Nội dung tiếng Việt
            $table->text('en_text')->nullable(); // Nội dung tiếng Anh
            $table->timestamps();

            $table->unique(['lang_code']); // Mỗi mã khóa là duy nhất
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('language_translations');
    }
};
