<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTpReceivingProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tp_receiving_product', function (Blueprint $table) {
            $table->uuid('receiving_product_id')->primary();
            $table->string('receiving_product_number');
            $table->uuid('po_supplier_id');
            $table->string('do_number_supplier');
            $table->string('delivered_by');
            $table->string('received_by');
            $table->date('date_receive');
            $table->tinyInteger('is_manually');
            $table->string('created_user')->nullable();
            $table->string('updated_user')->nullable();
            $table->tinyInteger('is_active');
            $table->timestamps();

            $table->foreign('po_supplier_id')->references('po_supplier_id')->on('tp_po_supplier');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tp_receiving_product');
    }
}
