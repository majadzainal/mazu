<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTmCustomerCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tm_customer_category', function (Blueprint $table) {
            $table->increments('customer_category_id');
            $table->string('cust_category_code');
            $table->string('cust_category_name');
            $table->string('cust_category_description')->nullable();
            $table->float('discount_percent');
            $table->uuid('store_id');
            $table->tinyInteger('is_active');
            $table->timestamps();

            $table->foreign('store_id')->references('store_id')->on('tm_store');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tm_customer_category');
    }
}
