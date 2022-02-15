<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTpSalesOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tp_sales_order', function (Blueprint $table) {
            $table->uuid('so_id')->primary();
            $table->string('so_number')->nullable();
            $table->date('so_date');
            $table->string('so_type');
            $table->string('medsos_description')->nullable();
            $table->uuid('endorse_id')->nullable();
            $table->uuid('customer_id')->nullable();
            $table->uuid('outlet_id')->nullable();
            $table->uuid('exc_reseller_id')->nullable();
            $table->uuid('owner_id')->nullable();
            $table->string('description')->nullable();
            $table->decimal('total_hpp', 11, 2)->nullable();
            $table->decimal('total_price', 11, 2)->nullable();
            $table->decimal('percent_discount', 11, 2)->nullable();
            $table->decimal('total_price_after_discount', 11, 2)->nullable();
            $table->decimal('ppn', 11, 2)->nullable();
            $table->decimal('grand_total', 11, 2)->nullable();
            $table->decimal('dec_paid', 11, 2)->nullable();
            $table->decimal('dec_remain', 11, 2)->nullable();
            $table->tinyInteger('is_process');
            $table->tinyInteger('is_draft');
            $table->tinyInteger('is_void');
            $table->uuid('store_id');
            $table->tinyInteger('is_active');
            $table->string('created_user')->nullable();
            $table->string('updated_user')->nullable();
            $table->timestamps();

            $table->foreign('endorse_id')->references('endorse_id')->on('tm_endorse');
            $table->foreign('customer_id')->references('customer_id')->on('tm_customer');
            $table->foreign('outlet_id')->references('outlet_id')->on('tm_outlet');
            $table->foreign('exc_reseller_id')->references('exc_reseller_id')->on('tm_exc_reseller');
            $table->foreign('owner_id')->references('owner_id')->on('tm_owner');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tp_sales_order');
    }
}
