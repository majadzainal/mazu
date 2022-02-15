<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTmPaidTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tm_paid_type', function (Blueprint $table) {
            $table->uuid('paid_type_id')->primary();
            $table->string('type_name');
            $table->string('account_name')->nullable();
            $table->string('account_number')->nullable();
            $table->uuid('store_id');
            $table->tinyInteger('is_credit');
            $table->tinyInteger('is_active');
            $table->string('created_user')->nullable();
            $table->string('updated_user')->nullable();
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
        Schema::dropIfExists('tm_paid_type');
    }
}
