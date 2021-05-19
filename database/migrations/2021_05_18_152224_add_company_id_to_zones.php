<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\User;
use App\Zone;
use Illuminate\Support\Facades\DB;

class AddCompanyIdToZones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('zones', function (Blueprint $table) {
            $table->unsignedBigInteger('company_id')->nullable()->index();
//            $table->unsignedBigInteger('customer_id')->nullable()->change();
        });

        DB::statement('ALTER TABLE zones
                   ALTER COLUMN customer_id SET default NULL');

        User::all()->each(function ($user, $key) {
            Zone::where('customer_id', $user->id)->update([
                'company_id' => $user->company_id
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('zones', function (Blueprint $table) {
            $table->dropColumn('company_id');
        });
    }
}
