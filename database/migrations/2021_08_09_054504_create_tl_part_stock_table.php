<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTlPartStockTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tl_part_stock', function (Blueprint $table) {
            $table->increments('part_stock_id');
            $table->uuid('part_customer_id')->nullable();
            $table->uuid('part_supplier_id')->nullable();
            $table->unsignedInteger('warehouse_id');
            $table->float('qty');
            $table->string('type');
            $table->string('description')->nullable();
            $table->date('date_log');
            $table->string('created_user');
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
        Schema::dropIfExists('tl_part_stock');
    }
}
