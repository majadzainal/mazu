<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTpPoSupplierTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tp_po_supplier', function (Blueprint $table) {
            $table->uuid('po_supplier_id')->primary();
            $table->string('po_number')->nullable();
            $table->date('po_date');
            $table->date('due_date');
            $table->uuid('supplier_id');
            $table->string('description')->nullable();
            $table->float('total_price')->nullable();
            $table->float('percent_discount')->nullable();
            $table->float('total_price_after_disount')->nullable();
            $table->tinyInteger('is_process');
            $table->tinyInteger('is_draft');
            $table->tinyInteger('is_void');
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
        Schema::dropIfExists('tp_po_supplier');
    }
}
