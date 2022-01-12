<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTmPartPriceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tm_part_price', function (Blueprint $table) {
            $table->increments('price_id');
            $table->uuid('part_supplier_id')->nullable();
            $table->uuid('part_customer_id')->nullable();
            $table->date('effective_date')->nullable();
            $table->float('price')->nullable();
            $table->tinyInteger('is_active');
            $table->string('created_user')->nullable();
            $table->string('updated_user')->nullable();
            $table->timestamps();

            $table->foreign('part_supplier_id')->references('part_supplier_id')->on('tm_part_supplier');
            $table->foreign('part_customer_id')->references('part_customer_id')->on('tm_part_customer');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tm_part_price');
    }
}
