<?php

use Illuminate\Database\Seeder;

class MachineTagsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::table('machine_tags')->delete();

        $machine_tags = [
            [ 'name' => 'Blender Capability', 'configuration_id' => MACHINE_BD_BATCH_BLENDER, 'tag_id' => 17, 'type' => 'line' ],
            [ 'name' => 'Process Rate', 'configuration_id' => MACHINE_BD_BATCH_BLENDER, 'tag_id' => 18, 'type' => 'line' ],
            [ 'name' => 'Load cell A zero bits', 'configuration_id' => MACHINE_BD_BATCH_BLENDER, 'tag_id' => 20, 'type' => 'line' ],
            [ 'name' => 'Load cell A cal bits', 'configuration_id' => MACHINE_BD_BATCH_BLENDER, 'tag_id' => 21, 'type' => 'line'],
            [ 'name' => 'Load cell B zero bits', 'configuration_id' => MACHINE_BD_BATCH_BLENDER, 'tag_id' => 22, 'type' => 'line'],
            [ 'name' => 'Load cell B cal bits', 'configuration_id' => MACHINE_BD_BATCH_BLENDER, 'tag_id' => 23, 'type' => 'line'],
            [ 'name' => 'Weigh hopper tare', 'configuration_id' => MACHINE_BD_BATCH_BLENDER, 'tag_id' => 24, 'type' => 'line'],
            [ 'name' => 'Capacity Utilization', 'configuration_id' => MACHINE_BD_BATCH_BLENDER, 'tag_id' => 2, 'type' => 'line'],
            [ 'name' => 'Energy Consumption', 'configuration_id' => MACHINE_BD_BATCH_BLENDER, 'tag_id' => 3, 'type' => 'line'],
        ];

        DB::table('machine_tags')->insert($machine_tags);
    }
}
