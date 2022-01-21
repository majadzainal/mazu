<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTmStoreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tm_store', function (Blueprint $table) {
            $table->uuid('store_id')->primary();
            $table->string('store_name')->nullable();
            $table->string('description')->nullable();
            $table->string('store_telephone')->nullable();
            $table->string('store_fax')->nullable();
            $table->string('store_email')->nullable();
            $table->string('store_address')->nullable();
            $table->string('npwp')->nullable();
            $table->string('logo')->nullable();
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
        Schema::dropIfExists('tm_store');
    }
}
