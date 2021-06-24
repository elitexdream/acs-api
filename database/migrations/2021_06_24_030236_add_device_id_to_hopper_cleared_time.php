<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeviceIdToHopperClearedTime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hopper_cleared_time', function (Blueprint $table) {
            $table->unsignedBigInteger('device_id')->default(0);
            $table->unsignedBigInteger('machine_id')->default(1);
        });

        DB::statement('ALTER TABLE hopper_cleared_time
                    ALTER COLUMN serial_number SET default 0');
        
        DB::statement('ALTER TABLE hopper_cleared_time
                    ALTER COLUMN serial_number TYPE bigint USING serial_number::bigint');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hopper_cleared_time', function (Blueprint $table) {
            //
        });
    }
}
