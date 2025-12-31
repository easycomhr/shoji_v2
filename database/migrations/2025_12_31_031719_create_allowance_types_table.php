<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAllowanceTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('allowance_types', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('company_id')->nullable()->default(null);

            $table->string('code', 50)->nullable();
            $table->string('name', 255)->nullable();
            $table->string('note', 255)->nullable();
            $table->tinyInteger('is_tax')->nullable()->default(0);
            $table->tinyInteger('is_social_insurance')->nullable()->default(0);
            $table->tinyInteger('status')->nullable()->default(0);

            $table->integer('created_user')->nullable();
            $table->integer('updated_user')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('allowance_types');
    }
}