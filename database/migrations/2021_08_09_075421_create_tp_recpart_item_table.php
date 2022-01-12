<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTpRecpartItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tp_recpart_item', function (Blueprint $table) {
            $table->increments('recpart_item_id');
            $table->uuid('recpart_supplier_id');
            $table->unsignedInteger('poitem_id');
            $table->unsignedInteger('warehouse_id');
            $table->float('qty_in');
            $table->float('qty_remain');
            $table->float('qty_over');
            $table->json('part_label_list')->nullable();
            $table->tinyInteger('is_active');
            $table->timestamps();

            $table->foreign('recpart_supplier_id')->references('recpart_supplier_id')->on('tp_recpart_supplier');
            $table->foreign('poitem_id')->references('poitem_id')->on('tp_po_item');
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
        Schema::dropIfExists('tp_recpart_item');
    }
}
