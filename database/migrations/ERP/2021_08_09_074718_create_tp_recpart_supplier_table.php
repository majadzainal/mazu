<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTpRecpartSupplierTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tp_recpart_supplier', function (Blueprint $table) {
            $table->uuid('recpart_supplier_id')->primary();
            $table->string('recpart_number');
            $table->uuid('po_id');
            $table->string('do_number_supplier');
            $table->string('delivered_by');
            $table->string('received_by');
            $table->date('date_receive');
            $table->tinyInteger('is_manually');
            $table->string('created_user')->nullable();
            $table->string('updated_user')->nullable();
            $table->tinyInteger('is_active');
            $table->timestamps();

            $table->foreign('po_id')->references('po_id')->on('tp_purchase_order');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tp_recpart_supplier');
    }
}
