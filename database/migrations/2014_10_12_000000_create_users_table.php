<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tm_user', function (Blueprint $table) {
            $table->uuid('user_id')->primary();
            $table->string('username')->unique();
            $table->string('password');
            $table->timestamp('email_verified_at')->nullable();
            $table->smallInteger('role');
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
        Schema::dropIfExists('tm_user');
    }
}
