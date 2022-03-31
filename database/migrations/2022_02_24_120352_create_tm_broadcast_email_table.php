<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTmBroadcastEmailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tm_broadcast_email', function (Blueprint $table) {
            $table->increments('broadcast_email_id');
            $table->string('subject')->nullable();
            $table->longText('header_text')->nullable();
            $table->longText('opening_text')->nullable();
            $table->longText('content_text')->nullable();
            $table->string('regards_text')->nullable();
            $table->string('regards_value_text')->nullable();
            $table->longText('footer_text')->nullable();
            $table->string('banner')->nullable();
            $table->uuid('store_id');
            $table->tinyInteger('is_birthday');
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
        Schema::dropIfExists('tm_broadcast_email');
    }
}
