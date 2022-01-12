<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTmBillMaterialTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tm_bill_material', function (Blueprint $table) {
            $table->uuid('bill_material_id')->primary();
            $table->uuid('customer_id');
            $table->uuid('part_customer_id');
            $table->date('date_input')->nullable();
            $table->smallInteger('status_id')->nullable();
            $table->tinyInteger('is_active');
            $table->string('created_user')->nullable();
            $table->string('updated_user')->nullable();
            $table->timestamps();

            $table->foreign('customer_id')->references('customer_id')->on('tm_customer');
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
        Schema::dropIfExists('tm_bill_material');
    }
}
