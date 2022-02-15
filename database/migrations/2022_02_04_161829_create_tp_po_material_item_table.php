<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTpPoMaterialItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tp_po_material_item', function (Blueprint $table) {
            $table->increments('po_material_item_id');
            $table->uuid('po_material_id');
            $table->uuid('product_supplier_id');
            $table->integer('qty_order')->nullable();
            $table->bigInteger('price')->nullable();
            $table->bigInteger('percent_discount')->nullable();
            $table->bigInteger('total_price')->nullable();
            $table->bigInteger('total_price_after_discount')->nullable();
            $table->string('description')->nullable();
            $table->integer('order_item');
            $table->timestamps();

            $table->foreign('po_material_id')->references('po_material_id')->on('tp_po_material');
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
        Schema::dropIfExists('tp_po_material_item');
    }
}
