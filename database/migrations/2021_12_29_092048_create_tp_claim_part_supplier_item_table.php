<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTpClaimPartSupplierItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tp_claim_part_supplier_item', function (Blueprint $table) {
            $table->increments('claim_psupplier_item_id');
            $table->uuid('claim_id');
            $table->uuid('part_supplier_id');
            $table->float('qty');
            $table->string('unit')->nullable();
            $table->integer('price')->nullable();
            $table->integer('order_item');
            $table->timestamps();

            $table->foreign('claim_id')->references('claim_id')->on('tp_claim_part_supplier');
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
        Schema::dropIfExists('tp_claim_part_supplier_item');
    }
}
