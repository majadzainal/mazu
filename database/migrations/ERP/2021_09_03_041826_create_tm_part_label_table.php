<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTmPartLabelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tm_part_label', function (Blueprint $table) {
            $table->increments('part_label_id');
            $table->string('part_label');
            $table->uuid('print_id');
            $table->uuid('part_supplier_id')->nullable();
            $table->uuid('part_customer_id')->nullable();
            $table->smallInteger('standard_packing');
            $table->tinyInteger('is_print');
            $table->tinyInteger('is_checking');
            $table->datetime('checking_at');
            $table->timestamps();

            $table->foreign('part_supplier_id')->references('part_supplier_id')->on('tm_part_supplier');
            $table->foreign('part_customer_id')->references('part_customer_id')->on('tm_part_customer');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tm_part_label');
    }
}
