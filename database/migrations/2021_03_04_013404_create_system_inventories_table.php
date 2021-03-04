<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_inventories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inventory')->default(0);
            $table->unsignedBigInteger('material_id');
            $table->unsignedBigInteger('location_id')->nullable();
            $table->unsignedBigInteger('serial_number');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('system_inventories');
    }
}
