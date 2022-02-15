<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTpDoOutletItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tp_do_outlet_item', function (Blueprint $table) {
            $table->increments('do_outlet_item_id');
            $table->uuid('do_outlet_id');
            $table->uuid('product_id');
            $table->integer('qty')->nullable();
            $table->unsignedInteger('warehouse_id');
            $table->string('description')->nullable();
            $table->longText('product_label_list')->nullable();
            $table->integer('order_item');
            $table->timestamps();

            $table->foreign('do_outlet_id')->references('do_outlet_id')->on('tp_do_outlet');
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
        Schema::dropIfExists('tp_do_outlet_item');
    }
}
