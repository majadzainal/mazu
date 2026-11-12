<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTpCashFlowTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tp_cash_flow', function (Blueprint $table) {
            $table->uuid('cash_flow_id')->primary();
            $table->string('cash_flow_code')->nullable();
            $table->string('cash_flow_name')->nullable();
            $table->text('description')->nullable();
            $table->date('cash_flow_date');
            $table->decimal('dec_cash_flow', 11, 2)->nullable();
            $table->string('cash_flow_type')->nullable();
            $table->string('created_user')->nullable();
            $table->string('updated_user')->nullable();
            $table->string('deleted_user')->nullable();
            $table->uuid('store_id');
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
        Schema::dropIfExists('tp_cash_flow');
    }
}
