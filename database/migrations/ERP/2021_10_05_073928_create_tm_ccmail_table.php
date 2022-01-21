<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTmCcmailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tm_ccmail', function (Blueprint $table) {
            $table->increments('ccmail_id');
            $table->string('ccmail_name');
            $table->string('ccmail_email');
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
        Schema::dropIfExists('tm_ccmail');
    }
}
