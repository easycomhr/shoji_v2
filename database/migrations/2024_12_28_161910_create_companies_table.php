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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Tên công ty
            $table->string('official_number')->unique(); // Mã số công ty chính thức (duy nhất)
            $table->string('address_line_1'); // Địa chỉ dòng 1
            $table->string('address_line_2')->nullable(); // Địa chỉ dòng 2 (có thể null)
            $table->string('address_line_3')->nullable(); // Địa chỉ dòng 3 (có thể null)
            $table->string('address_line_4')->nullable(); // Địa chỉ dòng 4 (có thể null)
            $table->string('address_line_5')->nullable(); // Địa chỉ dòng 5 (có thể null)
            $table->string('address_line_6')->nullable(); // Địa chỉ dòng 6 (có thể null)
            $table->string('phone')->nullable(); // Số điện thoại (có thể null)
            $table->string('fax')->nullable(); // Số fax (có thể null)
            $table->string('email')->unique(); // Địa chỉ email (duy nhất)
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
