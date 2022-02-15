<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTpSalesOrderItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tp_sales_order_item', function (Blueprint $table) {
            $table->increments('so_item_id');
            $table->uuid('so_id');
            $table->uuid('product_id');
            $table->integer('qty_order')->nullable();
            $table->decimal('hpp', 11, 2)->nullable();
            $table->decimal('price', 11, 2)->nullable();
            $table->decimal('percent_discount', 11, 2)->nullable();
            $table->decimal('total_price', 11, 2)->nullable();
            $table->decimal('total_price_after_discount', 11, 2)->nullable();
            $table->string('description')->nullable();
            $table->json('product_label_list')->nullable();
            $table->integer('order_item');
            $table->timestamps();

            $table->foreign('product_id')->references('product_id')->on('tm_product');
            $table->foreign('so_id')->references('so_id')->on('tp_sales_order');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tp_sales_order_item');
    }
}
