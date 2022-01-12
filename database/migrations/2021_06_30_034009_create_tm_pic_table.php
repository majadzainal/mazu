<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTmPicTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tm_pic', function (Blueprint $table) {
            $table->uuid('pic_id')->primary();
            $table->uuid('supplier_id')->nullable();
            $table->uuid('customer_id')->nullable();
            $table->unsignedInteger('pic_type_id');
            $table->string('pic_name')->nullable();
            $table->string('pic_telephone')->nullable();
            $table->string('pic_email')->nullable();
            $table->timestamps();

            $table->foreign('supplier_id')->references('supplier_id')->on('tm_supplier');
            $table->foreign('customer_id')->references('customer_id')->on('tm_customer');
            $table->foreign('pic_type_id')->references('pic_type_id')->on('tm_pic_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tm_pic');
    }
}
