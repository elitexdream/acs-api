<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHopperClearedTimeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hopper_cleared_time', function (Blueprint $table) {
            $table->id();
            $table->string('serial_number', 20)->default('');
            $table->unsignedBigInteger('timestamp');
            $table->timestamp('last_cleared_time')->nullable();
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
        Schema::dropIfExists('hopper_cleared_time');
    }
}
