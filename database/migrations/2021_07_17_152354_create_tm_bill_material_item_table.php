<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTmBillMaterialItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tm_bill_material_item', function (Blueprint $table) {
            $table->increments('item_bom_id');
            $table->uuid('bill_material_id');
            $table->uuid('part_id');
            $table->float('amount_usage')->nullable();
            $table->unsignedInteger('unit_id')->nullable();
            $table->integer('price')->nullable();
            $table->float('cost')->nullable();
            $table->tinyInteger('is_active');
            $table->string('created_user')->nullable();
            $table->string('updated_user')->nullable();
            $table->timestamps();

            $table->foreign('bill_material_id')->references('bill_material_id')->on('tm_bill_material');
            $table->foreign('unit_id')->references('unit_id')->on('tm_unit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tm_bill_material_item');
    }
}
