<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTmProccessMachineTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tm_process_machine', function (Blueprint $table) {
            $table->increments('pmachine_id');
            $table->unsignedInteger('divisi_id')->nullable();
            $table->unsignedInteger('plant_id')->nullable();
            $table->string('code')->nullable();
            $table->string('brand')->nullable();
            $table->string('line')->nullable();
            $table->float('spec_volume')->nullable();
            $table->unsignedInteger('spec_unit')->nullable();
            $table->string('cycle_time')->nullable();
            $table->string('uos')->nullable();
            $table->tinyInteger('is_active');
            $table->string('created_user')->nullable();
            $table->string('updated_user')->nullable();
            $table->timestamps();

            $table->foreign('divisi_id')->references('divisi_id')->on('tm_divisi');
            $table->foreign('plant_id')->references('plant_id')->on('tm_plant');
            $table->foreign('spec_unit')->references('unit_id')->on('tm_unit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tm_process_machine');
    }
}
