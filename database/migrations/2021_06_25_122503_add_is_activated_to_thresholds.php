<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsActivatedToThresholds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('thresholds', function (Blueprint $table) {
            $table->boolean('threshold_activated')->default(false);
            $table->boolean('approaching_activated')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('thresholds', function (Blueprint $table) {
            //
        });
    }
}
