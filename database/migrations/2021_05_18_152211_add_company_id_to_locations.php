<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\User;
use App\Location;
use Illuminate\Support\Facades\DB;

class AddCompanyIdToLocations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->unsignedBigInteger('company_id')->nullable()->index();
            //$table->unsignedBigInteger('customer_id')->nullable()->change();
        });

        DB::statement('ALTER TABLE locations
                    ALTER COLUMN customer_id SET default NULL');

        User::all()->each(function ($user, $key) {
            Location::where('customer_id', $user->id)->update([
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
        Schema::table('locations', function (Blueprint $table) {
            $table->dropColumn('company_id');
        });
    }
}
