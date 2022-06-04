<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsInvoiceToTmPaidTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tm_paid_type', function (Blueprint $table) {
            $table->tinyinteger('is_invoice_bank');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tm_paid_type', function (Blueprint $table) {
            $table->dropColumn('is_invoice_bank');
        });
    }
}
