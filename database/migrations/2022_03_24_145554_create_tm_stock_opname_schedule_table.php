<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTmStockOpnameScheduleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tm_stock_opname_schedule', function (Blueprint $table) {
            $table->uuid('stock_opname_schedule_id')->primary();
            $table->date('opname_date');
            $table->datetime('start_datetime');
            $table->datetime('end_datetime')->nullable();
            $table->tinyinteger('is_closed');
            $table->string('user_created');
            $table->string('user_closed')->nullable();
            $table->tinyinteger('is_active');
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
        Schema::dropIfExists('tm_stock_opname_schedule');
    }
}
