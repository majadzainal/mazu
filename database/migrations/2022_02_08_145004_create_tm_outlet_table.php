<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTmOutletTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tm_outlet', function (Blueprint $table) {
            $table->uuid('outlet_id')->primary();
            $table->string('outlet_code')->nullable();
            $table->string('outlet_name')->nullable();
            $table->string('description')->nullable();
            $table->string('address')->nullable();
            $table->uuid('store_id');
            $table->unsignedInteger('warehouse_id');
            $table->tinyInteger('is_active');
            $table->string('created_user')->nullable();
            $table->string('updated_user')->nullable();
            $table->timestamps();

            $table->foreign('warehouse_id')->references('warehouse_id')->on('tm_warehouse');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tm_outlet');
    }
}
