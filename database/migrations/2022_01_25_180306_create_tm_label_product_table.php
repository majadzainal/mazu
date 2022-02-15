<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTmLabelProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tm_label_product', function (Blueprint $table) {
            $table->increments('label_product_id');
            $table->string('no_label');
            $table->uuid('product_id');
            $table->tinyInteger('is_print');
            $table->tinyInteger('is_checked_in');
            $table->tinyInteger('is_checked_out');
            $table->uuid('store_id');
            $table->string('created_user')->nullable();
            $table->string('updated_user')->nullable();
            $table->timestamps();

            $table->foreign('product_id')->references('product_id')->on('tm_product');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tm_label_product');
    }
}
