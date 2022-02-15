<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTmProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tm_product', function (Blueprint $table) {
            $table->uuid('product_id')->primary();
            $table->string('product_code');
            $table->string('product_name');
            $table->string('product_description')->nullable();
            $table->decimal('price')->nullable();
            $table->decimal('hpp')->nullable();
            $table->integer('stock')->nullable();
            $table->unsignedInteger('unit_id')->nullable();
            $table->unsignedInteger('product_category_id');
            $table->uuid('store_id');
            $table->string('images')->nullable();
            $table->tinyInteger('is_active');
            $table->string('created_user')->nullable();
            $table->string('updated_user')->nullable();
            $table->timestamps();

            $table->foreign('product_category_id')->references('product_category_id')->on('tm_product_category');
            $table->foreign('unit_id')->references('unit_id')->on('tm_unit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tm_product');
    }
}
