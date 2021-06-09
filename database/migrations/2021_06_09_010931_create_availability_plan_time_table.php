<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAvailabilityPlanTimeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('availability_plan_time', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('timestamp');
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('hours');

            $table->foreign('company_id')->references('id')->on('companies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('availability_plan_time');
    }
}
