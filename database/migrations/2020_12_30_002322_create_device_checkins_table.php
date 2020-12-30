<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeviceCheckinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device_checkins', function (Blueprint $table) {
            $table->id();
            $table->string('device_id', 50);
            $table->string('ts', 50);
            $table->string('sdk', 50)->nullable();
            $table->string('acs_sha1', 50)->nullable();
            $table->string('config_hash', 50)->nullable();
            $table->text('status')->nullable();
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
        Schema::dropIfExists('device_checkins');
    }
}
