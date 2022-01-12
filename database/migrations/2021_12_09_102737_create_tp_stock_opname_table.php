<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTpStockOpnameTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tp_stock_opname', function (Blueprint $table) {
            $table->uuid('stock_opname_id')->primary();
            $table->string('stock_opname_number');
            $table->uuid('opname_schedule_id');
            $table->unsignedInteger('plant_id');
            $table->integer('opname_type_id');
            $table->date('stock_opname_date');
            $table->string('description')->nullable();
            $table->string('created_user')->nullable();
            $table->string('updated_user')->nullable();
            $table->tinyInteger('is_active');
            $table->timestamps();

            $table->foreign('opname_schedule_id')->references('opname_schedule_id')->on('tp_opname_schedule');
            $table->foreign('plant_id')->references('plant_id')->on('tm_plant');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tp_stock_opname');
    }
}
