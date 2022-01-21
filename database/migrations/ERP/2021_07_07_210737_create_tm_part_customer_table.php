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
            $table->unsignedInteger('divisi_id')->nullable();
            $table->string('part_number')->nullable();
            $table->string('part_name')->nullable();
            $table->date('add_date')->nullable();
            $table->unsignedInteger('plant_id')->nullable();
            $table->float('price')->nullable();
            $table->float('stock')->nullable();
            $table->unsignedInteger('unit_id')->nullable();
            $table->string('standard_order')->nullable();
            $table->unsignedInteger('status')->nullable();
            $table->tinyInteger('is_supplier')->nullable();
            $table->tinyInteger('is_active');
            $table->string('created_user')->nullable();
            $table->string('updated_user')->nullable();
            $table->timestamps();

            $table->foreign('customer_id')->references('customer_id')->on('tm_customer');
            $table->foreign('divisi_id')->references('divisi_id')->on('tm_divisi');
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
