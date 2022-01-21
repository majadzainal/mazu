<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTpBudgetingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tp_budgeting', function (Blueprint $table) {
            $table->uuid('budgeting_id')->primary();
            $table->uuid('sales_order_id');
            $table->string('month_periode');
            $table->string('year_periode');
            $table->uuid('part_supplier_id');
            $table->float('qty');
            $table->float('price');
            $table->float('total_price');
            $table->timestamps();

            $table->foreign('sales_order_id')->references('sales_order_id')->on('tp_sales_order');
            $table->foreign('part_supplier_id')->references('part_supplier_id')->on('tm_part_supplier');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tp_budgeting');
    }
}
