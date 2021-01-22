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
        Schema::create('device_relations', function (Blueprint $table) {
            $table->id();
            $table->string('teltonike_id', 50);
            $table->string('device_type', 50);
            $table->string('device_serial_number', 50);
            $table->boolean('device_plc_link')->default(false);

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
        Schema::dropIfExists('device_relations');
    }
}
