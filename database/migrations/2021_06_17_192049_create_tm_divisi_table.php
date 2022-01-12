<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTmDivisiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tm_divisi', function (Blueprint $table) {
            $table->increments('divisi_id');
            $table->string('divisi_code');
            $table->string('divisi_name');
            $table->integer('part_type_id')->nullable();
            $table->tinyInteger('is_production');
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
        Schema::dropIfExists('tm_divisi');
    }
}
