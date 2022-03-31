<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTpGeneralLedgerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tp_general_ledger', function (Blueprint $table) {
            $table->increments('general_ledger_id');
            $table->date('gl_date');
            $table->decimal('debit', 11, 2)->nullable();
            $table->decimal('credit', 11, 2)->nullable();
            $table->uuid('po_customer_id')->nullable();
            $table->uuid('po_supplier_id')->nullable();
            $table->uuid('production_id')->nullable();
            $table->uuid('po_material_id')->nullable();
            $table->uuid('sales_order_paid_id')->nullable();
            $table->uuid('cash_out_id')->nullable();
            $table->uuid('store_id');
            $table->string('created_user')->nullable();
            $table->string('updated_user')->nullable();
            $table->string('deleted_user')->nullable();
            $table->timestamps();

            $table->foreign('po_customer_id')->references('po_customer_id')->on('tp_po_customer');
            $table->foreign('po_supplier_id')->references('po_supplier_id')->on('tp_po_supplier');
            $table->foreign('production_id')->references('production_id')->on('tp_production');
            $table->foreign('po_material_id')->references('po_material_id')->on('tp_po_material');
            $table->foreign('sales_order_paid_id')->references('sales_order_paid_id')->on('tp_sales_order_paid');
            $table->foreign('cash_out_id')->references('cash_out_id')->on('tp_cash_out');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tp_general_ledger');
    }
}
