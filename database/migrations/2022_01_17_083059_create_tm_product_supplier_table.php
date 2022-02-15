<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTmProductSupplierTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tm_product_supplier', function (Blueprint $table) {
            $table->uuid('product_supplier_id')->primary();
            $table->string('product_code');
            $table->string('product_name');
            $table->string('product_description')->nullable();
            $table->decimal('price')->nullable();
            $table->decimal('stock')->nullable();
            $table->unsignedInteger('unit_id')->nullable();
            $table->unsignedInteger('product_category_id');
            $table->uuid('store_id');
            $table->string('images')->nullable();
            $table->tinyInteger('is_service');
            $table->tinyInteger('is_active');
            $table->string('created_user')->nullable();
            $table->string('updated_user')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tm_product_supplier');
    }
}
