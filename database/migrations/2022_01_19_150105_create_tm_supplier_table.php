<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTmSupplierTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tm_supplier', function (Blueprint $table) {
            $table->uuid('supplier_id')->primary();
            $table->string('supplier_name');
            $table->date('date_of_birth');
            $table->string('description')->nullable();
            $table->string('address')->nullable();
            $table->uuid('store_id');
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
        Schema::dropIfExists('tm_supplier');
    }
}
