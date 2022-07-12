<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCashFlowIdToTpGeneralLedgerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tp_general_ledger', function (Blueprint $table) {
            $table->uuid('cash_flow_id')->nullable();

            $table->foreign('cash_flow_id')->references('cash_flow_id')->on('tp_cash_flow');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tp_general_ledger', function (Blueprint $table) {
            //
        });
    }
}
