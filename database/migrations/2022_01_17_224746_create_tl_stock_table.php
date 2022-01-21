<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTlStockTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tl_stock', function (Blueprint $table) {
            $table->increments('log_stock_id');
            $table->uuid('product_id');
            $table->unsignedInteger('warehouse_id');
            $table->float('qty');
            $table->string('type');
            $table->string('description')->nullable();
            $table->date('date_log');
            $table->string('created_user');
            $table->uuid('store_id');
            $table->timestamps();

            $table->foreign('product_id')->references('product_id')->on('tm_product');
            $table->foreign('warehouse_id')->references('warehouse_id')->on('tm_warehouse');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tl_stock');
    }
}
