<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTmCustomerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tm_customer', function (Blueprint $table) {
            $table->uuid('customer_id')->primary();
            $table->string('customer_code')->nullable();
            $table->string('business_entity')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('customer_telephone')->nullable();
            $table->string('customer_fax')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('customer_address')->nullable();
            $table->string('billing_address')->nullable();
            $table->string('delivery_address')->nullable();
            $table->integer('pay_time')->nullable();
            $table->string('bank')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('npwp')->nullable();
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
        Schema::dropIfExists('tm_customer');
    }
}
