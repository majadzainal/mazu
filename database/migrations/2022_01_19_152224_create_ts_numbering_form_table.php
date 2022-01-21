<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTsNumberingFormTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ts_numbering_form', function (Blueprint $table) {
            $table->increments('numbering_form_id');
            $table->string('numbering_form_type')->unique();
            $table->string('numbering_form_name')->unique();
            $table->string('string_val')->nullable();
            $table->tinyInteger('string_used');
            $table->integer('year_val')->nullable();
            $table->tinyInteger('year_used');
            $table->tinyInteger('month_used');
            $table->tinyInteger('day_used');
            $table->unsignedInteger('counter_id');
            $table->uuid('store_id');
            $table->string('updated_user')->nullable();
            $table->timestamps();

            $table->foreign('counter_id')->references('counter_id')->on('ts_counter');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ts_numbering_form');
    }
}
