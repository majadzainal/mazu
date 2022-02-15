<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTpDoOutletTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tp_do_outlet', function (Blueprint $table) {
            $table->uuid('do_outlet_id')->primary();
            $table->string('do_number')->nullable();
            $table->date('do_date');
            $table->uuid('outlet_id');
            $table->string('description')->nullable();
            $table->tinyInteger('is_process');
            $table->tinyInteger('is_draft');
            $table->tinyInteger('is_void');
            $table->uuid('store_id');
            $table->tinyInteger('is_active');
            $table->string('created_user')->nullable();
            $table->string('updated_user')->nullable();
            $table->timestamps();

            $table->foreign('outlet_id')->references('outlet_id')->on('tm_outlet');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tp_do_outlet');
    }
}
