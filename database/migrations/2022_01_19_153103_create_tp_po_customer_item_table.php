<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTpPoCustomerItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tp_po_customer_item', function (Blueprint $table) {
            $table->increments('po_customer_item_id');
            $table->uuid('po_customer_id');
            $table->uuid('product_id');
            $table->integer('qty_order')->nullable();
            $table->bigInteger('price')->nullable();
            $table->bigInteger('percent_discount')->nullable();
            $table->bigInteger('total_price')->nullable();
            $table->bigInteger('total_price_after_discount')->nullable();
            $table->string('description')->nullable();
            $table->integer('order_item');
            $table->timestamps();

            $table->foreign('po_customer_id')->references('po_customer_id')->on('tp_po_customer');
            $table->foreign('product_id')->references('product_id')->on('tm_product');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tp_po_customer_item');
    }
}
