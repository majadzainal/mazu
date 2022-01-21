<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTpSalesOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tp_sales_order', function (Blueprint $table) {
            $table->uuid('sales_order_id')->primary();
            $table->string('so_number')->nullable();
            $table->date('so_date')->nullable();
            $table->string('month_periode');
            $table->string('year_periode');
            $table->string('po_number_customer')->nullable();
            $table->date('po_date_customer')->nullable();
            $table->uuid('customer_id');
            $table->float('total_price')->nullable();
            $table->string('pic_sales_name')->nullable();
            $table->string('pic_sales_telephone')->nullable();
            $table->string('pic_sales_email')->nullable();
            $table->tinyInteger('is_process');
            $table->tinyInteger('is_draft');
            $table->tinyInteger('is_void');
            $table->tinyInteger('is_active');
            $table->string('created_user')->nullable();
            $table->string('updated_user')->nullable();
            $table->timestamps();

            $table->foreign('customer_id')->references('customer_id')->on('tm_customer');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tp_sales_order');
    }
}
