<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReasonIdToDowntimes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('downtimes', function (Blueprint $table) {
            $table->dropColumn('reason');
            $table->integer('reason_id')->default(5);
            
            $table->foreign('reason_id')->references('id')->on('downtime_reasons');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('downtimes', function (Blueprint $table) {
            $table->dropColumn('reason_id');
        });
    }
}
