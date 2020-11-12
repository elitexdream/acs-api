<?php

use Illuminate\Database\Seeder;

class MachineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('machines')->delete();

        $machines = [ [
                'id' => 1,
                'name' => 'BD Batch Blender',
            ], [
                'id' => 2,
                'name' => 'Accumeter Ovation Continuous',
            ], [
                'id' => 3,
                'name' => 'GH Gravimetric Extrusion Control',
            ], [
                'id' => 4,
                'name' => 'GH-F Gravimetric Additive',
            ], [
                'id' => 5,
                'name' => 'VTC Plus Conveying',
            ], [
                'id' => 6,
                'name' => 'NGX',
            ], [
                'id' => 7,
                'name' => 'NGX Nomad',
            ], [
                'id' => 8,
                'name' => 'Truetemp',
            ], [
                'id' => 9,
                'name' => 'GP & HE Central',
            ], [
                'id' => 10,
                'name' => 'T50 Central',
            ]
        ];
        DB::table('machines')->insert($machines);
    }
}