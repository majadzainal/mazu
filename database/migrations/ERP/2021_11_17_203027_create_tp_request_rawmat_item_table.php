<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTpRequestRawmatItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tp_request_rawmat_item', function (Blueprint $table) {
            $table->increments('request_item_id');
            $table->uuid('request_id');
            $table->uuid('part_id');
            $table->unsignedInteger('warehouse_id');
            $table->float('qty');
            $table->string('unit')->nullable();
            $table->integer('price')->nullable();
            $table->string('note')->nullable();
            $table->timestamps();

            $table->foreign('request_id')->references('request_id')->on('tp_request_rawmat');
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
        Schema::dropIfExists('tp_request_rawmat_item');
    }
}
