<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEmailSmsToThresholds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('thresholds', function (Blueprint $table) {
            $table->boolean('sms_checked')->default(false);
            $table->boolean('email_checked')->default(true);
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
            $table->dropColumn('sms_checked');
            $table->dropColumn('email_checked');
        });
    }
}
