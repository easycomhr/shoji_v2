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
        Schema::create('offices', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('company_id')->nullable()->comment('companies::id');
            $table->string('code')->nullable();
            $table->string('name')->nullable();
            $table->string('address_line_1')->nullable(); // Địa chỉ dòng 1
            $table->string('address_line_2')->nullable(); // Địa chỉ dòng 2 (có thể null)
            $table->string('phone')->nullable(); // Số điện thoại (có thể null)
            $table->string('fax')->nullable(); // Số fax (có thể null)
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offices');
    }
};
