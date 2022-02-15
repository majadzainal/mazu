<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTpProductionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tp_production', function (Blueprint $table) {
            $table->uuid('production_id')->primary();
            $table->string('po_number')->nullable();
            $table->uuid('po_customer_id')->nullable();
            $table->date('po_date');
            $table->date('due_date');
            $table->uuid('supplier_id');
            $table->string('description')->nullable();
            $table->bigInteger('total_price')->nullable();
            $table->bigInteger('percent_discount')->nullable();
            $table->bigInteger('total_price_after_discount')->nullable();
            $table->bigInteger('ppn')->nullable();
            $table->bigInteger('grand_total')->nullable();
            $table->tinyInteger('is_process');
            $table->tinyInteger('is_open');
            $table->tinyInteger('is_draft');
            $table->tinyInteger('is_void');
            $table->uuid('store_id');
            $table->tinyInteger('is_active');
            $table->string('created_user')->nullable();
            $table->string('updated_user')->nullable();
            $table->timestamps();

            $table->foreign('po_customer_id')->references('po_customer_id')->on('tp_po_customer');
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
        Schema::dropIfExists('tp_production');
    }
}
