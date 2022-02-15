<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTpReceivingProductSupplierItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tp_rec_prod_supplier_item', function (Blueprint $table) {
            $table->increments('rec_prod_supplier_item_id');
            $table->uuid('rec_prod_supplier_id');
            $table->unsignedInteger('po_material_item_id');
            $table->unsignedInteger('warehouse_id');
            $table->float('qty_in');
            $table->float('qty_remain');
            $table->float('qty_over');
            $table->timestamps();

            $table->foreign('rec_prod_supplier_id')->references('rec_prod_supplier_id')->on('tp_rec_prod_supplier');
            $table->foreign('po_material_item_id')->references('po_material_item_id')->on('tp_po_material_item');
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
        Schema::dropIfExists('tp_receiving_product_supplier_item');
    }
}
