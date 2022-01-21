<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTmProccessPriceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tm_process_price', function (Blueprint $table) {
            $table->increments('process_price_id');
            $table->unsignedInteger('process_id');
            $table->unsignedInteger('pmachine_id');
            $table->integer('cycle')->nullable();
            $table->date('effective_date');
            $table->float('price')->nullable();
            $table->tinyInteger('is_active');
            $table->string('created_user')->nullable();
            $table->string('updated_user')->nullable();
            $table->timestamps();

            $table->foreign('process_id')->references('process_id')->on('tm_process');
            $table->foreign('pmachine_id')->references('pmachine_id')->on('tm_process_machine');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tm_process_price');
    }
}
