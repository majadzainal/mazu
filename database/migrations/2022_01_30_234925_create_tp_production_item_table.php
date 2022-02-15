<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTpProductionItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tp_production_item', function (Blueprint $table) {
            $table->increments('production_item_id');
            $table->uuid('production_id');
            $table->uuid('product_supplier_id');
            $table->integer('qty_order')->nullable();
            $table->bigInteger('price')->nullable();
            $table->bigInteger('percent_discount')->nullable();
            $table->bigInteger('total_price')->nullable();
            $table->bigInteger('total_price_after_discount')->nullable();
            $table->string('description')->nullable();
            $table->integer('order_item');
            $table->timestamps();

            $table->foreign('production_id')->references('production_id')->on('tp_production');
            $table->foreign('product_supplier_id')->references('product_supplier_id')->on('tm_product_supplier');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tp_production_item');
    }
}
