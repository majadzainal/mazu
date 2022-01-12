<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTmPositionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tm_position', function (Blueprint $table) {
            $table->increments('position_id');
            $table->string('position_name');
            $table->boolean('is_active');
            $table->timestamps();

            $table->dropPrimary('position_id');
            $table->primary(array('position_id'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tm_position');
    }
}
