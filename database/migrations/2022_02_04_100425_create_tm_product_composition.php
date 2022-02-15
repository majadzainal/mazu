<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTmProductComposition extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tm_product_composition', function (Blueprint $table) {
            $table->increments('product_composition_id');
            $table->uuid('product_id');
            $table->uuid('product_supplier_id');
            $table->float('amount_usage')->nullable();
            $table->integer('order_item');
            $table->timestamps();

            $table->foreign('product_id')->references('product_id')->on('tm_product');
            $table->foreign('product_supplier_id')->references('product_supplier_id')->on('tm_product_supplier');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tm_product_composition');
    }
}
