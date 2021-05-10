<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToDeviceConfigurations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE device_configurations
                    ALTER COLUMN teltonika_id TYPE bigint USING teltonika_id::bigint,
                    ALTER COLUMN plc_type TYPE bigint USING plc_type::bigint,
                    ALTER COLUMN plc_serial_number TYPE bigint USING plc_serial_number::bigint,
                    ALTER COLUMN tcu_type TYPE bigint USING tcu_type::bigint,
                    ALTER COLUMN tcu_serial_number TYPE bigint USING tcu_serial_number::bigint');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('device_configurations', function (Blueprint $table) {
            //
        });
    }
}
