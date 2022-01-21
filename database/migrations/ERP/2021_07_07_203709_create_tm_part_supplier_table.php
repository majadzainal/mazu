<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTmPartSupplierTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tm_part_supplier', function (Blueprint $table) {
            $table->uuid('part_supplier_id')->primary();
            $table->uuid('supplier_id');
            $table->unsignedInteger('divisi_id')->nullable();
            $table->string('part_number')->nullable();
            $table->string('part_name')->nullable();
            $table->date('add_date')->nullable();
            $table->float('price')->nullable();
            $table->float('stock')->nullable();
            $table->unsignedInteger('unit_id')->nullable();
            $table->string('standard_packing')->nullable();
            $table->float('minimum_stock');
            $table->smallInteger('status')->nullable();
            $table->tinyInteger('is_active');
            $table->string('created_user')->nullable();
            $table->string('updated_user')->nullable();
            $table->timestamps();

            $table->foreign('supplier_id')->references('supplier_id')->on('tm_supplier');
            $table->foreign('divisi_id')->references('divisi_id')->on('tm_divisi');
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
        Schema::dropIfExists('tm_part_supplier');
    }
}
