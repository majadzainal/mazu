<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTmEmployeeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tm_employee', function (Blueprint $table) {
            $table->uuid('employee_id')->primary();
            $table->uuid('user_id');
            $table->string('employee_name')->nullable();
            $table->smallInteger('divisi_id')->nullable();
            $table->smallInteger('location_id')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->tinyInteger('is_active');
            $table->string('created_user')->nullable();
            $table->string('updated_user')->nullable();
            $table->foreign('user_id')->references('user_id')->on('tm_user')
            ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tm_employee');
    }
}
