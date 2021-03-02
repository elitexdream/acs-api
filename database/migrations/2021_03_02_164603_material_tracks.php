<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MaterialTracks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('material_tracks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inventory_material_id');
            $table->unsignedBigInteger('start')->nullable();
            $table->unsignedBigInteger('stop')->nullable();
            $table->boolean('in_progress')->default(false);

            $table->foreign('inventory_material_id')->references('id')->on('inventory_materials');

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
        Schema::dropIfExists('material_tracks');
    }
}
