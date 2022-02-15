<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTmStockExcResellerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tm_stock_exc_reseller', function (Blueprint $table) {
            $table->uuid('stock_exc_reseller_id')->primary();
            $table->uuid('product_id')->nullable();
            $table->uuid('product_supplier_id')->nullable();
            $table->unsignedInteger('warehouse_id');
            $table->integer('stock');
            $table->uuid('exc_reseller_id');
            $table->uuid('store_id');
            $table->string('created_user')->nullable();
            $table->string('updated_user')->nullable();
            $table->timestamps();

            $table->foreign('product_id')->references('product_id')->on('tm_product');
            $table->foreign('product_supplier_id')->references('product_supplier_id')->on('tm_product_supplier');
            $table->foreign('warehouse_id')->references('warehouse_id')->on('tm_warehouse');
            $table->foreign('exc_reseller_id')->references('exc_reseller_id')->on('tm_exc_reseller');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tm_stock_exc_reseller');
    }
}
