<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeviceTypeToMachinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('machines', function (Blueprint $table) {
            $table->integer('device_type')->default(0);
        });
        Schema::table('machines', function (Blueprint $table) {
            $table->dropColumn('serial_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('machines', function (Blueprint $table) {
            $table->string('serial_number', 20)->default('');
        });
        Schema::table('machines', function (Blueprint $table) {
            $table->dropColumn('device_type');
        });
    }
}
