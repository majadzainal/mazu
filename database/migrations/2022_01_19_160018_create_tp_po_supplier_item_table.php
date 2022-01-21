<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTpPoSupplierItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tp_po_supplier_item', function (Blueprint $table) {
            $table->increments('po_supplier_item_id');
            $table->uuid('po_supplier_id');
            $table->uuid('product_id');
            $table->float('price')->nullable();
            $table->float('percent_discount')->nullable();
            $table->float('total_price')->nullable();
            $table->float('total_price_after_disount')->nullable();
            $table->string('description')->nullable();
            $table->integer('order_item');
            $table->tinyInteger('is_process');
            $table->tinyInteger('is_draft');
            $table->tinyInteger('is_void');
            $table->tinyInteger('is_active');
            $table->string('created_user')->nullable();
            $table->string('updated_user')->nullable();
            $table->timestamps();

            $table->foreign('po_supplier_id')->references('po_supplier_id')->on('tp_po_supplier');
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
        Schema::dropIfExists('tp_po_supplier_item');
    }
}
