<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToDowntimes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('downtimes', function (Blueprint $table) {
            $table->unsignedBigInteger('running_start_id')->nullable();
            $table->unsignedBigInteger('running_end_id')->nullable();
            $table->unsignedBigInteger('idle_start_id')->nullable();
            $table->unsignedBigInteger('idle_end_id')->nullable();
            $table->unsignedBigInteger('foreign_type')->nullable();

            $table->foreign('running_start_id')->references('id')->on('runnings');
            $table->foreign('running_end_id')->references('id')->on('runnings');
            $table->foreign('idle_start_id')->references('id')->on('idle');
            $table->foreign('idle_end_id')->references('id')->on('idle');
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
            $table->dropColumn('running_start_id');
            $table->dropColumn('running_end_id');
            $table->dropColumn('idle_start_id');
            $table->dropColumn('idle_end_id');
            $table->dropColumn('foreign_type');
        });
    }
}
