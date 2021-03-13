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
            [ 'name' => 'Capacity Utilization', 'configuration_id' => MACHINE_BD_BATCH_BLENDER, 'tag_id' => 2, 'type' => 'line', 'offset' => 0, 'divided_by' => 10 ],
            [ 'name' => 'Energy Consumption', 'configuration_id' => MACHINE_BD_BATCH_BLENDER, 'tag_id' => 3, 'type' => 'line', 'offset' => 0, 'divided_by' => 1 ],
            [ 'name' => 'Blender Capability', 'configuration_id' => MACHINE_BD_BATCH_BLENDER, 'tag_id' => 17, 'type' => 'line', 'offset' => 0, 'divided_by' => 1 ],
            [ 'name' => 'Process Rate', 'configuration_id' => MACHINE_BD_BATCH_BLENDER, 'tag_id' => 18, 'type' => 'line', 'offset' => 0, 'divided_by' => 1 ],
            [ 'name' => 'Load cell A zero bits', 'configuration_id' => MACHINE_BD_BATCH_BLENDER, 'tag_id' => 20, 'type' => 'line', 'offset' => 0, 'divided_by' => 1 ],
            [ 'name' => 'Load cell A cal bits', 'configuration_id' => MACHINE_BD_BATCH_BLENDER, 'tag_id' => 21, 'type' => 'line', 'offset' => 0, 'divided_by' => 1 ],
            [ 'name' => 'Load cell B zero bits', 'configuration_id' => MACHINE_BD_BATCH_BLENDER, 'tag_id' => 22, 'type' => 'line', 'offset' => 0, 'divided_by' => 1 ],
            [ 'name' => 'Load cell B cal bits', 'configuration_id' => MACHINE_BD_BATCH_BLENDER, 'tag_id' => 23, 'type' => 'line', 'offset' => 0, 'divided_by' => 1 ],
            [ 'name' => 'Weigh hopper tare', 'configuration_id' => MACHINE_BD_BATCH_BLENDER, 'tag_id' => 24, 'type' => 'line', 'offset' => 0, 'divided_by' => 1000 ],
            [ 'name' => 'Target Weight[1]', 'configuration_id' => MACHINE_BD_BATCH_BLENDER, 'tag_id' => 13, 'type' => 'line', 'offset' => 0, 'divided_by' => 1000 ],
            [ 'name' => 'Target Weight[2]', 'configuration_id' => MACHINE_BD_BATCH_BLENDER, 'tag_id' => 13, 'type' => 'line', 'offset' => 1, 'divided_by' => 1000 ],
            [ 'name' => 'Target Weight[3]', 'configuration_id' => MACHINE_BD_BATCH_BLENDER, 'tag_id' => 13, 'type' => 'line', 'offset' => 2, 'divided_by' => 1000 ],
            [ 'name' => 'Target Weight[4]', 'configuration_id' => MACHINE_BD_BATCH_BLENDER, 'tag_id' => 13, 'type' => 'line', 'offset' => 3, 'divided_by' => 1000 ],
            [ 'name' => 'Target Weight[5]', 'configuration_id' => MACHINE_BD_BATCH_BLENDER, 'tag_id' => 13, 'type' => 'line', 'offset' => 4, 'divided_by' => 1000 ],
            [ 'name' => 'Target Weight[6]', 'configuration_id' => MACHINE_BD_BATCH_BLENDER, 'tag_id' => 13, 'type' => 'line', 'offset' => 5, 'divided_by' => 1000 ],
            [ 'name' => 'Target Weight[7]', 'configuration_id' => MACHINE_BD_BATCH_BLENDER, 'tag_id' => 13, 'type' => 'line', 'offset' => 6, 'divided_by' => 1000 ],
            [ 'name' => 'Target Weight[8]', 'configuration_id' => MACHINE_BD_BATCH_BLENDER, 'tag_id' => 13, 'type' => 'line', 'offset' => 7, 'divided_by' => 1000 ],
            [ 'name' => 'Actual Weight[1]', 'configuration_id' => MACHINE_BD_BATCH_BLENDER, 'tag_id' => 14, 'type' => 'line', 'offset' => 0, 'divided_by' => 1000 ],
            [ 'name' => 'Actual Weight[2]', 'configuration_id' => MACHINE_BD_BATCH_BLENDER, 'tag_id' => 14, 'type' => 'line', 'offset' => 1, 'divided_by' => 1000 ],
            [ 'name' => 'Actual Weight[3]', 'configuration_id' => MACHINE_BD_BATCH_BLENDER, 'tag_id' => 14, 'type' => 'line', 'offset' => 2, 'divided_by' => 1000 ],
            [ 'name' => 'Actual Weight[4]', 'configuration_id' => MACHINE_BD_BATCH_BLENDER, 'tag_id' => 14, 'type' => 'line', 'offset' => 3, 'divided_by' => 1000 ],
            [ 'name' => 'Actual Weight[5]', 'configuration_id' => MACHINE_BD_BATCH_BLENDER, 'tag_id' => 14, 'type' => 'line', 'offset' => 4, 'divided_by' => 1000 ],
            [ 'name' => 'Actual Weight[6]', 'configuration_id' => MACHINE_BD_BATCH_BLENDER, 'tag_id' => 14, 'type' => 'line', 'offset' => 5, 'divided_by' => 1000 ],
            [ 'name' => 'Actual Weight[7]', 'configuration_id' => MACHINE_BD_BATCH_BLENDER, 'tag_id' => 14, 'type' => 'line', 'offset' => 6, 'divided_by' => 1000 ],
            [ 'name' => 'Actual Weight[8]', 'configuration_id' => MACHINE_BD_BATCH_BLENDER, 'tag_id' => 14, 'type' => 'line', 'offset' => 7, 'divided_by' => 1000 ],
            [ 'name' => 'Calibration Factor[1]', 'configuration_id' => MACHINE_BD_BATCH_BLENDER, 'tag_id' => 19, 'type' => 'line', 'offset' => 0, 'divided_by' => 100 ],
            [ 'name' => 'Calibration Factor[2]', 'configuration_id' => MACHINE_BD_BATCH_BLENDER, 'tag_id' => 19, 'type' => 'line', 'offset' => 1, 'divided_by' => 100 ],
            [ 'name' => 'Calibration Factor[3]', 'configuration_id' => MACHINE_BD_BATCH_BLENDER, 'tag_id' => 19, 'type' => 'line', 'offset' => 2, 'divided_by' => 100 ],
            [ 'name' => 'Calibration Factor[4]', 'configuration_id' => MACHINE_BD_BATCH_BLENDER, 'tag_id' => 19, 'type' => 'line', 'offset' => 3, 'divided_by' => 100 ],
            [ 'name' => 'Calibration Factor[5]', 'configuration_id' => MACHINE_BD_BATCH_BLENDER, 'tag_id' => 19, 'type' => 'line', 'offset' => 4, 'divided_by' => 100 ],
            [ 'name' => 'Calibration Factor[6]', 'configuration_id' => MACHINE_BD_BATCH_BLENDER, 'tag_id' => 19, 'type' => 'line', 'offset' => 5, 'divided_by' => 100 ],
            [ 'name' => 'Calibration Factor[7]', 'configuration_id' => MACHINE_BD_BATCH_BLENDER, 'tag_id' => 19, 'type' => 'line', 'offset' => 6, 'divided_by' => 100 ],
            [ 'name' => 'Calibration Factor[8]', 'configuration_id' => MACHINE_BD_BATCH_BLENDER, 'tag_id' => 19, 'type' => 'line', 'offset' => 7, 'divided_by' => 100 ],
            [ 'name' => 'Hopper Stable[1]', 'configuration_id' => MACHINE_BD_BATCH_BLENDER, 'tag_id' => 25, 'type' => 'line', 'offset' => 0, 'divided_by' => 1 ],
            [ 'name' => 'Hopper Stable[2]', 'configuration_id' => MACHINE_BD_BATCH_BLENDER, 'tag_id' => 25, 'type' => 'line', 'offset' => 1, 'divided_by' => 1 ],
            [ 'name' => 'Hopper Stable[3]', 'configuration_id' => MACHINE_BD_BATCH_BLENDER, 'tag_id' => 25, 'type' => 'line', 'offset' => 2, 'divided_by' => 1 ],
            [ 'name' => 'Hopper Stable[4]', 'configuration_id' => MACHINE_BD_BATCH_BLENDER, 'tag_id' => 25, 'type' => 'line', 'offset' => 3, 'divided_by' => 1 ],
            [ 'name' => 'Hopper Stable[5]', 'configuration_id' => MACHINE_BD_BATCH_BLENDER, 'tag_id' => 25, 'type' => 'line', 'offset' => 4, 'divided_by' => 1 ],
            [ 'name' => 'Hopper Stable[6]', 'configuration_id' => MACHINE_BD_BATCH_BLENDER, 'tag_id' => 25, 'type' => 'line', 'offset' => 5, 'divided_by' => 1 ],
            [ 'name' => 'Hopper Stable[7]', 'configuration_id' => MACHINE_BD_BATCH_BLENDER, 'tag_id' => 25, 'type' => 'line', 'offset' => 6, 'divided_by' => 1 ],
            [ 'name' => 'Hopper Stable[8]', 'configuration_id' => MACHINE_BD_BATCH_BLENDER, 'tag_id' => 25, 'type' => 'line', 'offset' => 7, 'divided_by' => 1 ],
        ];

        DB::table('machine_tags')->insert($machine_tags);
    }
}
