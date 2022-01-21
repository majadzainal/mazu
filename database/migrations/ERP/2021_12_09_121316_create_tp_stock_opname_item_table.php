<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTpStockOpnameItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tp_stock_opname_item', function (Blueprint $table) {
            $table->increments('stock_opname_item_id');
            $table->uuid('stock_opname_id');
            $table->uuid('part_supplier_id')->nullable();
            $table->uuid('part_customer_id')->nullable();
            $table->unsignedInteger('warehouse_id');
            $table->float('stock');
            $table->float('stock_adjustment');
            $table->float('deviation')->nullable();
            $table->unsignedInteger('unit_id');
            $table->string('note')->nullable();
            $table->integer('order_item');
            $table->timestamps();

            $table->foreign('stock_opname_id')->references('stock_opname_id')->on('tp_stock_opname');
            $table->foreign('part_supplier_id')->references('part_supplier_id')->on('tm_part_supplier');
            $table->foreign('part_customer_id')->references('part_customer_id')->on('tm_part_customer');
            $table->foreign('warehouse_id')->references('warehouse_id')->on('tm_warehouse');
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
        Schema::dropIfExists('tp_stock_opname_item');
    }
}
