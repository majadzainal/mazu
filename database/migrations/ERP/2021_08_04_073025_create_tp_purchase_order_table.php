<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTpPurchaseOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tp_purchase_order', function (Blueprint $table) {
            $table->uuid('po_id')->primary();
            $table->uuid('supplier_id');
            $table->string('po_number')->nullable();
            $table->date('po_date')->nullable();
            $table->smallInteger('month_periode')->nullable();
            $table->smallInteger('year_periode')->nullable();
            $table->float('price')->nullable();
            $table->float('ppn')->nullable();
            $table->float('total_price')->nullable();
            $table->tinyInteger('additional')->nullable();
            $table->tinyInteger('status_process')->nullable();
            $table->tinyInteger('is_open');
            $table->tinyInteger('is_active');
            $table->string('created_user')->nullable();
            $table->string('updated_user')->nullable();
            $table->timestamps();

            $table->foreign('supplier_id')->references('supplier_id')->on('tm_supplier');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tp_purchase_order');
    }
}
