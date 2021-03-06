<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTpPoCustomerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tp_po_customer', function (Blueprint $table) {
            $table->uuid('po_customer_id')->primary();
            $table->string('po_number')->nullable();
            $table->date('po_date');
            $table->date('due_date');
            $table->uuid('customer_id');
            $table->string('description')->nullable();
            $table->bigInteger('total_price')->nullable();
            $table->bigInteger('percent_discount')->nullable();
            $table->bigInteger('total_price_after_discount')->nullable();
            $table->bigInteger('ppn')->nullable();
            $table->decimal('shipping_cost', 11, 2)->nullable();
            $table->bigInteger('grand_total')->nullable();
            $table->decimal('grand_total_wshipping', 11, 2)->nullable();
            $table->decimal('dec_paid', 11, 2)->nullable();
            $table->decimal('dec_remain', 11, 2)->nullable();
            $table->uuid('paid_type_id')->nullable();
            $table->tinyInteger('is_so_created');
            $table->tinyInteger('is_process');
            $table->tinyInteger('is_open');
            $table->tinyInteger('is_draft');
            $table->tinyInteger('is_void');
            $table->uuid('store_id');
            $table->tinyInteger('is_active');
            $table->string('created_user')->nullable();
            $table->string('updated_user')->nullable();
            $table->timestamps();

            $table->foreign('customer_id')->references('customer_id')->on('tm_customer');
            $table->foreign('paid_type_id')->references('paid_type_id')->on('tm_paid_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tp_po_customer');
    }
}
