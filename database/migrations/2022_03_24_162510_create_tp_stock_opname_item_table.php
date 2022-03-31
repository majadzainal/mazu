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
            $table->uuid('product_id')->nullable();
            $table->uuid('product_supplier_id')->nullable();
            $table->smallInteger('warehouse_id');
            $table->float('stock');
            $table->float('stock_adjustment');
            $table->float('deviation')->nullable();
            $table->integer('unit_id');
            $table->string('note')->nullable();
            $table->integer('order_item');
            $table->timestamps();
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
