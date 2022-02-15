<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTpReceivingProductItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tp_receiving_product_item', function (Blueprint $table) {
            $table->increments('receiving_product_item_id');
            $table->uuid('receiving_product_id');
            $table->unsignedInteger('po_supplier_item_id');
            $table->unsignedInteger('warehouse_id');
            $table->float('qty_in');
            $table->float('qty_remain');
            $table->float('qty_over');
            $table->json('product_label_list')->nullable();
            $table->timestamps();

            $table->foreign('receiving_product_id')->references('receiving_product_id')->on('tp_receiving_product');
            $table->foreign('po_supplier_item_id')->references('po_supplier_item_id')->on('tp_po_supplier_item');
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
        Schema::dropIfExists('tp_receiving_product_item');
    }
}
