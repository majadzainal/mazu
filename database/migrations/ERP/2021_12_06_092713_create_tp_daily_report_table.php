<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTpDailyReportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tp_daily_report', function (Blueprint $table) {
            $table->uuid('report_id')->primary();
            $table->unsignedInteger('plant_id');
            $table->date('report_date');
            $table->string('description')->nullable();
            $table->string('created_user')->nullable();
            $table->string('updated_user')->nullable();
            $table->tinyInteger('is_status');
            $table->tinyInteger('is_active');
            $table->timestamps();

            $table->foreign('plant_id')->references('plant_id')->on('tm_plant');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tp_daily_report');
    }
}
