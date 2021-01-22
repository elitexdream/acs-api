<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeviceRelationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device_configurations', function (Blueprint $table) {
            $table->id();
            $table->string('teltonika_id', 50);

            $table->string('plc_type', 50)->nullable();
            $table->string('plc_serial_number', 50)->nullable();

            $table->string('tcu_type', 50)->nullable();
            $table->string('tcu_serial_number', 50)->nullable();

            $table->json('body');

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
        Schema::dropIfExists('device_configurations');
    }
}
