<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTpDailyReportItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tp_daily_report_item', function (Blueprint $table) {
            $table->increments('report_item_id');
            $table->uuid('report_id');
            $table->uuid('part_customer_id');
            $table->float('production_plan')->nullable();
            $table->float('actual')->nullable();
            $table->float('over_time')->nullable();
            $table->float('total')->nullable();
            $table->tinyInteger('is_wip')->nullable();
            $table->string('reference_id')->nullable();
            $table->timestamps();

            $table->foreign('report_id')->references('report_id')->on('tp_daily_report');
            $table->foreign('part_customer_id')->references('part_customer_id')->on('tm_part_customer');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tp_daily_report_item');
    }
}
