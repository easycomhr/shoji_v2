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
        Schema::create('user_leaves', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('company_id')->comment('companies::id');
            $table->bigInteger('user_id')->comment('users::id');
            $table->string('user_code')->comment('users::code');
            $table->date('register_date')->nullable()->comment('Date of leave registration');
            $table->date('leave_date')->nullable()->comment('The date staff absence');
            $table->bigInteger('leave_type_id');
            $table->integer('leave_session_id')->nullable()->comment('1: Morning, 2: Afternoon, 3: Wholeday');
            $table->double('leave_amount')->nullable()->default(0)->comment('Amount of leave taken');
            $table->string('comment', 255)->nullable()->comment('Additional comments');
            $table->integer('sys_marker')->default(0)->comment('To mark some records to know why it come here');
            $table->integer('approved')->default(0)->comment('0 is not yet approved, 1 is approved');

            // Indexes
            $table->index('user_id');
            $table->index('leave_type_id');
            $table->index('leave_date');
            $table->index('approved');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_leaves');
    }
};
