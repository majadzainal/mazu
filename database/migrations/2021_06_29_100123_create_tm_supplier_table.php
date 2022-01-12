<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTmSupplierTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tm_supplier', function (Blueprint $table) {
            $table->uuid('supplier_id')->primary();
            // $table->smallInteger('divisi_id')->nullable();
            $table->string('supplier_code')->nullable();
            $table->string('business_entity')->nullable();
            $table->string('supplier_name')->nullable();
            $table->string('supplier_telephone')->nullable();
            $table->string('supplier_fax')->nullable();
            $table->string('supplier_email')->nullable();
            $table->string('supplier_address')->nullable();
            $table->integer('pay_time')->nullable();
            $table->string('bank')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->tinyInteger('is_ppn');
            $table->string('npwp')->nullable();
            $table->tinyInteger('is_active');
            $table->timestamps();

            // $table->foreign('divisi_id')->references('divisi_id')->on('tm_divisi');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tm_supplier');
    }
}
