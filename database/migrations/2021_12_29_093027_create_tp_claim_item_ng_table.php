<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTpClaimItemNgTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tp_claim_item_ng', function (Blueprint $table) {
            $table->increments('claim_item_ng_id');
            $table->unsignedInteger('claim_psupplier_item_id');
            $table->unsignedInteger('ng_id');
            $table->float('qty');
            $table->string('unit')->nullable();
            $table->unsignedInteger('warehouse_id')->nullable();
            $table->string('description')->nullable();
            $table->integer('order_item');
            $table->timestamps();

            $table->foreign('claim_psupplier_item_id')->references('claim_psupplier_item_id')->on('tp_claim_part_supplier_item');
            $table->foreign('ng_id')->references('ng_id')->on('tm_ng');
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
        Schema::dropIfExists('tp_claim_item_ng');
    }
}
