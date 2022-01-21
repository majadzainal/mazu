<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTmStockTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tm_stock', function (Blueprint $table) {
            $table->uuid('stock_id')->primary();
            $table->uuid('part_customer_id')->nullable();
            $table->uuid('part_supplier_id')->nullable();
            $table->unsignedInteger('warehouse_id');
            $table->float('stock')->nullable();
            $table->string('created_user')->nullable();
            $table->string('updated_user')->nullable();
            $table->timestamps();

            $table->foreign('part_customer_id')->references('part_customer_id')->on('tm_part_customer');
            $table->foreign('part_supplier_id')->references('part_supplier_id')->on('tm_part_supplier');
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
        Schema::dropIfExists('tm_stock');
    }
}
