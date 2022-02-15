<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTmMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tm_menu', function (Blueprint $table) {
            $table->string('menu_id')->primary()->unique();
            $table->string('menu_name');
            $table->string('alias');
            $table->string('uri');
            $table->string('class');
            $table->tinyInteger('parent');
            $table->string('parent_menu');
            $table->smallInteger('order');
            $table->tinyInteger('is_superuser');
            $table->tinyInteger('is_active');
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
        Schema::dropIfExists('tm_menu');
    }
}
