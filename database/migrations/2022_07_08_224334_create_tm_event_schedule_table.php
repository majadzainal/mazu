<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTmEventScheduleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tm_event_schedule', function (Blueprint $table) {
            $table->uuid('event_schedule_id')->primary();
            $table->string('event_name');
            $table->string('description');
            $table->date('start_date');
            $table->date('end_date');
            $table->tinyinteger('is_closed');
            $table->string('user_created');
            $table->string('user_closed')->nullable();
            $table->tinyinteger('is_active');
            $table->uuid('store_id');
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
        Schema::dropIfExists('tm_event_schedule');
    }
}
