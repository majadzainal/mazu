<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTmPlantTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tm_plant', function (Blueprint $table) {
            $table->increments('plant_id');
            $table->string('plant_name');
            $table->string('description');
            $table->unsignedInteger('location_id');
            $table->tinyInteger('is_active');
            $table->timestamps();

            $table->foreign('location_id')->references('location_id')->on('tm_location');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tm_plant');
    }
}
