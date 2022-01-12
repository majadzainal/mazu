<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTmPartCustomerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tm_part_customer', function (Blueprint $table) {
            $table->uuid('part_customer_id')->primary();
            $table->uuid('customer_id');
            $table->string('part_number')->nullable();
            $table->string('part_name')->nullable();
            $table->date('add_date')->nullable();
            $table->smallInteger('plant_id')->nullable();
            $table->float('stock')->nullable();
            $table->smallInteger('unit_id')->nullable();
            $table->string('standard_order')->nullable();
            $table->smallInteger('status')->nullable();
            $table->tinyInteger('is_active');
            $table->string('created_user')->nullable();
            $table->string('updated_user')->nullable();
            $table->timestamps();

            $table->foreign('customer_id')->references('customer_id')->on('tm_customer');
            $table->foreign('plant_id')->references('plant_id')->on('tm_plant');
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
        Schema::dropIfExists('tm_part_customer');
    }
}
