<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTpSoItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tp_so_item', function (Blueprint $table) {
            $table->uuid('soitem_id')->primary();
            $table->uuid('sales_order_id');
            $table->uuid('part_customer_id');
            $table->integer('qty')->nullable();
            $table->unsignedInteger('unit_id')->nullable();
            $table->float('price')->nullable();
            $table->float('total_price')->nullable();
            $table->float('qtyM1');
            $table->float('qtyM2');
            $table->float('qtyM3');
            $table->float('qtyM4');
            $table->float('qtyM5');
            $table->unsignedInteger('plant_id');
            $table->unsignedInteger('divisi_id');
            $table->integer('order_item');
            $table->timestamps();

            $table->foreign('sales_order_id')->references('sales_order_id')->on('tp_sales_order');
            $table->foreign('part_customer_id')->references('part_customer_id')->on('tm_part_customer');
            $table->foreign('plant_id')->references('plant_id')->on('tm_plant');
            $table->foreign('divisi_id')->references('divisi_id')->on('tm_divisi');
            $table->foreign('unit_id')->references('unit_id')->on('tm_unit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tp_so_item');
    }
}
