<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTpStockOpnameTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tp_stock_opname', function (Blueprint $table) {
            $table->uuid('stock_opname_id')->primary();
            $table->uuid('stock_opname_schedule_id');
            $table->integer('opname_type_id');
            $table->date('stock_opname_date');
            $table->string('description')->nullable();
            $table->string('created_user')->nullable();
            $table->string('updated_user')->nullable();
            $table->tinyInteger('is_active');
            $table->uuid('store_id');
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
        Schema::dropIfExists('tp_stock_opname');
    }
}
