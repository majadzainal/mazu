<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTmWarehouseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tm_warehouse', function (Blueprint $table) {
            $table->increments('warehouse_id');
            $table->string('warehouse_name');
            $table->string('description')->nullable();
            $table->unsignedInteger('plant_id');
            $table->tinyInteger('is_active');
            $table->timestamps();
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
        Schema::dropIfExists('tm_warehouse');
    }
}
