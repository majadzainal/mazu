<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTpClaimPartSupplierTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tp_claim_part_supplier', function (Blueprint $table) {
            $table->uuid('claim_id')->primary();
            $table->uuid('supplier_id');
            $table->uuid('recpart_supplier_id')->nullable();
            $table->uuid('request_id')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();

            $table->foreign('supplier_id')->references('supplier_id')->on('tm_supplier');
            $table->foreign('recpart_supplier_id')->references('recpart_supplier_id')->on('tp_recpart_supplier');
            $table->foreign('request_id')->references('request_id')->on('tp_request_rawmat');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tp_claim_part_supplier');
    }
}
