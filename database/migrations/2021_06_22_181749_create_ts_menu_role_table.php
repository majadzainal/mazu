<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTsMenuRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ts_menu_role', function (Blueprint $table) {
            $table->increments('menu_role_id');
            $table->unsignedInteger('role_id');
            $table->string('menu_id');
            $table->tinyInteger('access');
            $table->tinyInteger('read');
            $table->tinyInteger('create');
            $table->tinyInteger('update');
            $table->tinyInteger('delete');

            $table->foreign('role_id')->references('role_id')->on('tm_role');
            $table->foreign('menu_id')->references('menu_id')->on('tm_menu');
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
        Schema::dropIfExists('ts_menu_role');
    }
}
