<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTmRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tm_role', function (Blueprint $table) {
            $table->increments('role_id');
            $table->string('role_name');
            $table->tinyInteger('is_active');
            $table->uuid('store_id')->nullable();
            $table->timestamps();

            $table->foreign('store_id')->references('store_id')->on('tm_store');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tm_role');
    }
}
