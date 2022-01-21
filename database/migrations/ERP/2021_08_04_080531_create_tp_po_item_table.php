<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTpPoItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tp_po_item', function (Blueprint $table) {
            $table->increments('poitem_id');
            $table->uuid('po_id');
            $table->uuid('part_supplier_id');
            $table->float('qty')->nullable();
            $table->float('qty_ng_rate')->nullable();
            $table->float('buffer_stock')->nullable();
            $table->float('stock')->nullable();
            $table->float('order')->nullable();
            $table->float('price')->nullable();
            $table->float('total_price')->nullable();
            $table->tinyInteger('is_active');
            $table->timestamps();

            $table->foreign('po_id')->references('po_id')->on('tp_purchase_order');
            $table->foreign('part_supplier_id')->references('part_supplier_id')->on('tm_part_supplier');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tp_po_item');
    }
}
