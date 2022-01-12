<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTpProductionScheduleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tp_production_schedule', function (Blueprint $table) {
            $table->increments('schedule_id');
            $table->uuid('sales_order_id')->nullable();
            $table->uuid('part_customer_id')->nullable();
            $table->date('schedule_date')->nullable();
            $table->tinyInteger('shift1')->nullable();
            $table->tinyInteger('shift2')->nullable();
            $table->float('qty')->nullable();
            $table->timestamps();

            $table->foreign('sales_order_id')->references('sales_order_id')->on('tp_sales_order');
            $table->foreign('part_customer_id')->references('part_customer_id')->on('tm_part_customer');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tp_production_schedule');
    }
}
