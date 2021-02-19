<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMaterialToDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('devices', function (Blueprint $table) {
            $table->unsignedBigInteger('material_id')->nullable();
            $table->unsignedBigInteger('material_location_id')->nullable();

            $table->foreign('material_id')->references('id')->on('materials');
            $table->foreign('material_location_id')->references('id')->on('material_locations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('devices', function (Blueprint $table) {
            $table->dropColumn('material_id');
            $table->dropColumn('material_location_id');
        });
    }
}
