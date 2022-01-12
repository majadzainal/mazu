<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTmBillProcessItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tm_bill_process_item', function (Blueprint $table) {
            $table->increments('item_bop_id');
            $table->uuid('bill_process_id');
            $table->smallInteger('process_order')->nullable();
            $table->unsignedInteger('process_id')->nullable();
            $table->smallInteger('cycle_time')->nullable();
            $table->unsignedInteger('mc')->nullable();
            $table->tinyInteger('is_active');
            $table->string('created_user')->nullable();
            $table->string('updated_user')->nullable();
            $table->timestamps();

            $table->foreign('bill_process_id')->references('bill_process_id')->on('tm_bill_process');
            $table->foreign('process_id')->references('process_id')->on('tm_process');
            $table->foreign('mc')->references('pmachine_id')->on('tm_process_machine');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tm_bill_process_item');
    }
}
