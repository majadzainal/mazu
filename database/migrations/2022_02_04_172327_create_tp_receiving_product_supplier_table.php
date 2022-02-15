<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTpReceivingProductSupplierTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tp_rec_prod_supplier', function (Blueprint $table) {
            $table->uuid('rec_prod_supplier_id')->primary();
            $table->string('rec_number');
            $table->uuid('po_material_id');
            $table->string('do_number_supplier');
            $table->string('delivered_by');
            $table->string('received_by');
            $table->date('date_receive');
            $table->string('created_user')->nullable();
            $table->string('updated_user')->nullable();
            $table->tinyInteger('is_active');
            $table->timestamps();

            $table->foreign('po_material_id')->references('po_material_id')->on('tp_po_material');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tp_receiving_product_supplier');
    }
}
