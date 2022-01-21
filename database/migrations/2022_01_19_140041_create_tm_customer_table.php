<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTmCustomerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tm_customer', function (Blueprint $table) {
            $table->uuid('customer_id')->primary();
            $table->string('customer_name');
            $table->date('date_of_birth');
            $table->string('description')->nullable();
            $table->string('address')->nullable();
            $table->unsignedInteger('customer_category_id');
            $table->uuid('store_id');
            $table->tinyInteger('is_active');
            $table->string('created_user')->nullable();
            $table->string('updated_user')->nullable();
            $table->timestamps();

            $table->foreign('customer_category_id')->references('customer_category_id')->on('tm_customer_category');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tm_customer');
    }
}
