<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTpSalesOrderPaidTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tp_sales_order_paid', function (Blueprint $table) {
            $table->increments('sales_order_paid_id');
            $table->uuid('so_id');
            $table->uuid('paid_type_id');
            $table->decimal('dec_paid', 11, 2)->nullable();
            $table->decimal('dec_remain', 11, 2)->nullable();
            $table->timestamps();

            $table->foreign('so_id')->references('so_id')->on('tp_sales_order');
            $table->foreign('paid_type_id')->references('paid_type_id')->on('tm_paid_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tp_sales_order_paid');
    }
}
