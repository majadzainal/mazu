<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTmProductCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tm_product_category', function (Blueprint $table) {
            $table->increments('product_category_id');
            $table->string('category_code');
            $table->string('category_name');
            $table->string('category_description')->nullable();
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
        Schema::dropIfExists('tm_product_category');
    }
}
