<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTlRequestRawmatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tl_request_rawmat', function (Blueprint $table) {
            $table->increments('log_req_id');
            $table->uuid('request_id');
            $table->string('comment')->nullable();
            $table->tinyInteger('status_process');
            $table->string('created_user');
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
        Schema::dropIfExists('tl_request_rawmat');
    }
}
