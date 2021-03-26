<?php

use Illuminate\Database\Seeder;

class AlarmTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('alarm_types')->delete();

        $alarm_types = [
            [
	          	'name' => 'Power Loss',
                'machine_id' => MACHINE_BD_BATCH_BLENDER,
                'tag_id' => 27,
                'offset' => 0,
                'bytes' => 0
            ], [
	          	'name' => 'Out of Material [1]',
                'machine_id' => MACHINE_BD_BATCH_BLENDER,
                'tag_id' => 28,
                'offset' => 0,
                'bytes' => 0
	      	], [
                'name' => 'Out of Material [2]',
                'machine_id' => MACHINE_BD_BATCH_BLENDER,
                'tag_id' => 28,
                'offset' => 1,
                'bytes' => 0
            ], [
                'name' => 'Out of Material [3]',
                'machine_id' => MACHINE_BD_BATCH_BLENDER,
                'tag_id' => 28,
                'offset' => 2,
                'bytes' => 0
            ], [
                'name' => 'Out of Material [4]',
                'machine_id' => MACHINE_BD_BATCH_BLENDER,
                'tag_id' => 28,
                'offset' => 3,
                'bytes' => 0
            ], [
                'name' => 'Out of Material [5]',
                'machine_id' => MACHINE_BD_BATCH_BLENDER,
                'tag_id' => 28,
                'offset' => 4,
                'bytes' => 0
            ], [
                'name' => 'Out of Material [6]',
                'machine_id' => MACHINE_BD_BATCH_BLENDER,
                'tag_id' => 28,
                'offset' => 5,
                'bytes' => 0
            ], [
                'name' => 'Out of Material [7]',
                'machine_id' => MACHINE_BD_BATCH_BLENDER,
                'tag_id' => 28,
                'offset' => 6,
                'bytes' => 0
            ], [
                'name' => 'Out of Material [8]',
                'machine_id' => MACHINE_BD_BATCH_BLENDER,
                'tag_id' => 28,
                'offset' => 7,
                'bytes' => 0
            ], [
	          	'name' => 'Hopper Unstable (Feeder 1)',
                'machine_id' => MACHINE_BD_BATCH_BLENDER,
                'tag_id' => 31,
                'offset' => 0,
                'bytes' => 0
	      	], [
                'name' => 'Hopper Unstable (Feeder 2)',
                'machine_id' => MACHINE_BD_BATCH_BLENDER,
                'tag_id' => 32,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'Hopper Unstable (Feeder 3)',
                'machine_id' => MACHINE_BD_BATCH_BLENDER,
                'tag_id' => 33,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'Hopper Unstable (Feeder 4)',
                'machine_id' => MACHINE_BD_BATCH_BLENDER,
                'tag_id' => 34,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'Hopper Unstable (Feeder 5)',
                'machine_id' => MACHINE_BD_BATCH_BLENDER,
                'tag_id' => 35,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'Hopper Unstable (Feeder 6)',
                'machine_id' => MACHINE_BD_BATCH_BLENDER,
                'tag_id' => 36,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'Hopper Unstable (Feeder 7)',
                'machine_id' => MACHINE_BD_BATCH_BLENDER,
                'tag_id' => 37,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'Hopper Unstable (Feeder 8)',
                'machine_id' => MACHINE_BD_BATCH_BLENDER,
                'tag_id' => 38,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'Hopper Overfeed [1]',
                'machine_id' => MACHINE_BD_BATCH_BLENDER,
                'tag_id' => 39,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'Hopper Overfeed [2]',
                'machine_id' => MACHINE_BD_BATCH_BLENDER,
                'tag_id' => 39,
                'offset' => 1,
                'bytes' => 0
            ], [
                'name' => 'Hopper Overfeed [3]',
                'machine_id' => MACHINE_BD_BATCH_BLENDER,
                'tag_id' => 39,
                'offset' => 2,
                'bytes' => 0
            ], [
                'name' => 'Hopper Overfeed [4]',
                'machine_id' => MACHINE_BD_BATCH_BLENDER,
                'tag_id' => 39,
                'offset' => 3,
                'bytes' => 0
            ], [
                'name' => 'Hopper Overfeed [5]',
                'machine_id' => MACHINE_BD_BATCH_BLENDER,
                'tag_id' => 39,
                'offset' => 4,
                'bytes' => 0
            ], [
                'name' => 'Hopper Overfeed [6]',
                'machine_id' => MACHINE_BD_BATCH_BLENDER,
                'tag_id' => 39,
                'offset' => 5,
                'bytes' => 0
            ], [
                'name' => 'Hopper Overfeed [7]',
                'machine_id' => MACHINE_BD_BATCH_BLENDER,
                'tag_id' => 39,
                'offset' => 6,
                'bytes' => 0
            ], [
                'name' => 'Hopper Overfeed [8]',
                'machine_id' => MACHINE_BD_BATCH_BLENDER,
                'tag_id' => 39,
                'offset' => 7,
                'bytes' => 0
            ], [
                'name' => 'Hopper Over Max',
                'machine_id' => MACHINE_BD_BATCH_BLENDER,
                'tag_id' => 40,
                'offset' => 0,
                'bytes' => 0
            ], [
				'name' => 'Unable to make rate',
                'machine_id' => MACHINE_BD_BATCH_BLENDER,
                'tag_id' => 43,
                'offset' => 0,
                'bytes' => 0
	      	], [
                'name' => 'Max Empty Weight',
                'machine_id' => MACHINE_BD_BATCH_BLENDER,
                'tag_id' => 41,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'Pumper Starter Fault',
                'machine_id' => MACHINE_BD_BATCH_BLENDER,
                'tag_id' => 44,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'Mixer Failure',
                'machine_id' => MACHINE_BD_BATCH_BLENDER,
                'tag_id' => 42,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'System Not Stable',
                'machine_id' => MACHINE_GH_GRAVIMETRIC_EXTRUSION_CONTROL_HOPPER,
                'tag_id' => 30,
                'offset' => 8,
                'bytes' => 1
            ], [
                'name' => 'Out Of Material',
                'machine_id' => MACHINE_GH_GRAVIMETRIC_EXTRUSION_CONTROL_HOPPER,
                'tag_id' => 30,
                'offset' => 0,
                'bytes' => 1
            ], [
                'name' => 'Load Cell Failure',
                'machine_id' => MACHINE_GH_GRAVIMETRIC_EXTRUSION_CONTROL_HOPPER,
                'tag_id' => 30,
                'offset' => 6,
                'bytes' => 1
            ], [
                'name' => 'No Extruder Flow',
                'machine_id' => MACHINE_GH_GRAVIMETRIC_EXTRUSION_CONTROL_HOPPER,
                'tag_id' => 30,
                'offset' => 4,
                'bytes' => 1
            ], [
                'name' => 'No Extruder RPM',
                'machine_id' => MACHINE_GH_GRAVIMETRIC_EXTRUSION_CONTROL_HOPPER,
                'tag_id' => 30,
                'offset' => 3,
                'bytes' => 1
            ], [
                'name' => 'Extruder Drive Failure',
                'machine_id' => MACHINE_GH_GRAVIMETRIC_EXTRUSION_CONTROL_HOPPER,
                'tag_id' => 30,
                'offset' => 17,
                'bytes' => 1
            ], [
                'name' => 'Hauloff Drive Failure',
                'machine_id' => MACHINE_GH_GRAVIMETRIC_EXTRUSION_CONTROL_HOPPER,
                'tag_id' => 30,
                'offset' => 5,
                'bytes' => 1
            ], [
                'name' => 'Extruder Underspeed',
                'machine_id' => MACHINE_GH_GRAVIMETRIC_EXTRUSION_CONTROL_HOPPER,
                'tag_id' => 30,
                'offset' => 11,
                'bytes' => 1
            ], [
                'name' => 'Extruder Overspeed',
                'machine_id' => MACHINE_GH_GRAVIMETRIC_EXTRUSION_CONTROL_HOPPER,
                'tag_id' => 30,
                'offset' => 10,
                'bytes' => 1
            ], [
                'name' => 'Hauloff Underspeed',
                'machine_id' => MACHINE_GH_GRAVIMETRIC_EXTRUSION_CONTROL_HOPPER,
                'tag_id' => 30,
                'offset' => 14,
                'bytes' => 1
            ], [
                'name' => 'Hauloff Overspeed',
                'machine_id' => MACHINE_GH_GRAVIMETRIC_EXTRUSION_CONTROL_HOPPER,
                'tag_id' => 30,
                'offset' => 13,
                'bytes' => 1
            ], [
                'name' => 'Hopper Over Max Flow',
                'machine_id' => MACHINE_GH_GRAVIMETRIC_EXTRUSION_CONTROL_HOPPER,
                'tag_id' => 30,
                'offset' => 9,
                'bytes' => 1
            ], [
                'name' => 'Out Of Material (Feeder 1)',
                'machine_id' => MACHINE_ACCUMETER_OVATION_CONTINUOUS_BLENDER,
                'tag_id' => 28,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'Out Of Material (Feeder 2)',
                'machine_id' => MACHINE_ACCUMETER_OVATION_CONTINUOUS_BLENDER,
                'tag_id' => 29,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'Out Of Material (Feeder 3)',
                'machine_id' => MACHINE_ACCUMETER_OVATION_CONTINUOUS_BLENDER,
                'tag_id' => 30,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'Out Of Material (Feeder 4)',
                'machine_id' => MACHINE_ACCUMETER_OVATION_CONTINUOUS_BLENDER,
                'tag_id' => 31,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'Out Of Material (Feeder 5)',
                'machine_id' => MACHINE_ACCUMETER_OVATION_CONTINUOUS_BLENDER,
                'tag_id' => 32,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'Out Of Material (Feeder 6)',
                'machine_id' => MACHINE_ACCUMETER_OVATION_CONTINUOUS_BLENDER,
                'tag_id' => 33,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'Load Cell Failure (Feeder 1)',
                'machine_id' => MACHINE_ACCUMETER_OVATION_CONTINUOUS_BLENDER,
                'tag_id' => 34,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'Load Cell Failure (Feeder 2)',
                'machine_id' => MACHINE_ACCUMETER_OVATION_CONTINUOUS_BLENDER,
                'tag_id' => 35,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'Load Cell Failure (Feeder 3)',
                'machine_id' => MACHINE_ACCUMETER_OVATION_CONTINUOUS_BLENDER,
                'tag_id' => 36,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'Load Cell Failure (Feeder 4)',
                'machine_id' => MACHINE_ACCUMETER_OVATION_CONTINUOUS_BLENDER,
                'tag_id' => 37,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'Load Cell Failure (Feeder 5)',
                'machine_id' => MACHINE_ACCUMETER_OVATION_CONTINUOUS_BLENDER,
                'tag_id' => 38,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'Load Cell Failure (Feeder 6)',
                'machine_id' => MACHINE_ACCUMETER_OVATION_CONTINUOUS_BLENDER,
                'tag_id' => 39,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'Load Cell Failure (Massflow Hopper)',
                'machine_id' => MACHINE_ACCUMETER_OVATION_CONTINUOUS_BLENDER,
                'tag_id' => 40,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'Load Cell Overload (Feeder 1)',
                'machine_id' => MACHINE_ACCUMETER_OVATION_CONTINUOUS_BLENDER,
                'tag_id' => 41,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'Load Cell Overload (Feeder 2)',
                'machine_id' => MACHINE_ACCUMETER_OVATION_CONTINUOUS_BLENDER,
                'tag_id' => 42,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'Load Cell Overload (Feeder 3)',
                'machine_id' => MACHINE_ACCUMETER_OVATION_CONTINUOUS_BLENDER,
                'tag_id' => 43,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'Load Cell Overload (Feeder 4)',
                'machine_id' => MACHINE_ACCUMETER_OVATION_CONTINUOUS_BLENDER,
                'tag_id' => 44,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'Load Cell Overload (Feeder 5)',
                'machine_id' => MACHINE_ACCUMETER_OVATION_CONTINUOUS_BLENDER,
                'tag_id' => 45,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'Load Cell Overload (Feeder 6)',
                'machine_id' => MACHINE_ACCUMETER_OVATION_CONTINUOUS_BLENDER,
                'tag_id' => 46,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'Load Cell Overload (Massflow Hopper)',
                'machine_id' => MACHINE_ACCUMETER_OVATION_CONTINUOUS_BLENDER,
                'tag_id' => 58,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'No Flow (Feeder 1)',
                'machine_id' => MACHINE_ACCUMETER_OVATION_CONTINUOUS_BLENDER,
                'tag_id' => 47,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'No Flow (Feeder 2)',
                'machine_id' => MACHINE_ACCUMETER_OVATION_CONTINUOUS_BLENDER,
                'tag_id' => 48,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'No Flow (Feeder 3)',
                'machine_id' => MACHINE_ACCUMETER_OVATION_CONTINUOUS_BLENDER,
                'tag_id' => 49,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'No Flow (Feeder 4)',
                'machine_id' => MACHINE_ACCUMETER_OVATION_CONTINUOUS_BLENDER,
                'tag_id' => 50,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'No Flow (Feeder 5)',
                'machine_id' => MACHINE_ACCUMETER_OVATION_CONTINUOUS_BLENDER,
                'tag_id' => 51,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'No Flow (Feeder 6)',
                'machine_id' => MACHINE_ACCUMETER_OVATION_CONTINUOUS_BLENDER,
                'tag_id' => 52,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'Unable to make rate',
                'machine_id' => MACHINE_ACCUMETER_OVATION_CONTINUOUS_BLENDER,
                'tag_id' => 53,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'Bad Blend',
                'machine_id' => MACHINE_ACCUMETER_OVATION_CONTINUOUS_BLENDER,
                'tag_id' => 54,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'Blender Rate Out Of Range',
                'machine_id' => MACHINE_ACCUMETER_OVATION_CONTINUOUS_BLENDER,
                'tag_id' => 55,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'System Not Stable',
                'machine_id' => MACHINE_GH_F_GRAVIMETRIC_ADDITIVE_FEEDER,
                'tag_id' => 40,
                'offset' => 9,
                'bytes' => 1
            ], [
                'name' => 'Out Of Material',
                'machine_id' => MACHINE_GH_F_GRAVIMETRIC_ADDITIVE_FEEDER,
                'tag_id' => 40,
                'offset' => 1,
                'bytes' => 1
            ], [
                'name' => 'Load Cell Failure',
                'machine_id' => MACHINE_GH_F_GRAVIMETRIC_ADDITIVE_FEEDER,
                'tag_id' => 40,
                'offset' => 7,
                'bytes' => 1
            ], [
                'name' => 'No Feeder Flow',
                'machine_id' => MACHINE_GH_F_GRAVIMETRIC_ADDITIVE_FEEDER,
                'tag_id' => 40,
                'offset' => 5,
                'bytes' => 1
            ], [
                'name' => 'Feeder Underspeed',
                'machine_id' => MACHINE_GH_F_GRAVIMETRIC_ADDITIVE_FEEDER,
                'tag_id' => 40,
                'offset' => 12,
                'bytes' => 1
            ], [
                'name' => 'Feeder Overspeed',
                'machine_id' => MACHINE_GH_F_GRAVIMETRIC_ADDITIVE_FEEDER,
                'tag_id' => 40,
                'offset' => 11,
                'bytes' => 1
            ], [
                'name' => 'Hopper Over Max Flow',
                'machine_id' => MACHINE_GH_F_GRAVIMETRIC_ADDITIVE_FEEDER,
                'tag_id' => 40,
                'offset' => 10,
                'bytes' => 1
            ], [
                'name' => 'Pump Oil Change Required [1]',
                'machine_id' => MACHINE_VTC_PLUS_CONVEYING_SYSTEM,
                'tag_id' => 18,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'Pump Oil Change Required [2]',
                'machine_id' => MACHINE_VTC_PLUS_CONVEYING_SYSTEM,
                'tag_id' => 18,
                'offset' => 1,
                'bytes' => 0
            ], [
                'name' => 'Pump Oil Change Required [3]',
                'machine_id' => MACHINE_VTC_PLUS_CONVEYING_SYSTEM,
                'tag_id' => 18,
                'offset' => 2,
                'bytes' => 0
            ], [
                'name' => 'Pump Oil Change Required [4]',
                'machine_id' => MACHINE_VTC_PLUS_CONVEYING_SYSTEM,
                'tag_id' => 18,
                'offset' => 3,
                'bytes' => 0
            ], [
                'name' => 'Pump Oil Change Required [5]',
                'machine_id' => MACHINE_VTC_PLUS_CONVEYING_SYSTEM,
                'tag_id' => 18,
                'offset' => 4,
                'bytes' => 0
            ], [
                'name' => 'Pump Oil Change Required [6]',
                'machine_id' => MACHINE_VTC_PLUS_CONVEYING_SYSTEM,
                'tag_id' => 18,
                'offset' => 5,
                'bytes' => 0
            ], [
                'name' => 'Pump Oil Change Required [7]',
                'machine_id' => MACHINE_VTC_PLUS_CONVEYING_SYSTEM,
                'tag_id' => 18,
                'offset' => 6,
                'bytes' => 0
            ], [
                'name' => 'Pump Oil Change Required [8]',
                'machine_id' => MACHINE_VTC_PLUS_CONVEYING_SYSTEM,
                'tag_id' => 18,
                'offset' => 7,
                'bytes' => 0
            ], [
                'name' => 'Pump Oil Change Required [9]',
                'machine_id' => MACHINE_VTC_PLUS_CONVEYING_SYSTEM,
                'tag_id' => 18,
                'offset' => 8,
                'bytes' => 0
            ], [
                'name' => 'Pump Oil Change Required [10]',
                'machine_id' => MACHINE_VTC_PLUS_CONVEYING_SYSTEM,
                'tag_id' => 18,
                'offset' => 9,
                'bytes' => 0
            ], [
                'name' => 'Pump Oil Change Required [11]',
                'machine_id' => MACHINE_VTC_PLUS_CONVEYING_SYSTEM,
                'tag_id' => 18,
                'offset' => 10,
                'bytes' => 0
            ], [
                'name' => 'Pump Oil Change Required [12]',
                'machine_id' => MACHINE_VTC_PLUS_CONVEYING_SYSTEM,
                'tag_id' => 18,
                'offset' => 11,
                'bytes' => 0
            ], [
                'name' => 'Pump Starter Fault [1]',
                'machine_id' => MACHINE_VTC_PLUS_CONVEYING_SYSTEM,
                'tag_id' => 20,
                'offset' => 0,
                'bytes' => 1
            ], [
                'name' => 'Pump Starter Fault [2]',
                'machine_id' => MACHINE_VTC_PLUS_CONVEYING_SYSTEM,
                'tag_id' => 20,
                'offset' => 1,
                'bytes' => 1
            ], [
                'name' => 'Pump Starter Fault [3]',
                'machine_id' => MACHINE_VTC_PLUS_CONVEYING_SYSTEM,
                'tag_id' => 20,
                'offset' => 2,
                'bytes' => 1
            ], [
                'name' => 'Pump Starter Fault [4]',
                'machine_id' => MACHINE_VTC_PLUS_CONVEYING_SYSTEM,
                'tag_id' => 20,
                'offset' => 3,
                'bytes' => 1
            ], [
                'name' => 'Pump Starter Fault [5]',
                'machine_id' => MACHINE_VTC_PLUS_CONVEYING_SYSTEM,
                'tag_id' => 20,
                'offset' => 4,
                'bytes' => 1
            ], [
                'name' => 'Pump Starter Fault [6]',
                'machine_id' => MACHINE_VTC_PLUS_CONVEYING_SYSTEM,
                'tag_id' => 20,
                'offset' => 5,
                'bytes' => 1
            ], [
                'name' => 'Pump Starter Fault [7]',
                'machine_id' => MACHINE_VTC_PLUS_CONVEYING_SYSTEM,
                'tag_id' => 20,
                'offset' => 6,
                'bytes' => 1
            ], [
                'name' => 'Pump Starter Fault [8]',
                'machine_id' => MACHINE_VTC_PLUS_CONVEYING_SYSTEM,
                'tag_id' => 20,
                'offset' => 7,
                'bytes' => 1
            ], [
                'name' => 'Pump Starter Fault [9]',
                'machine_id' => MACHINE_VTC_PLUS_CONVEYING_SYSTEM,
                'tag_id' => 20,
                'offset' => 8,
                'bytes' => 1
            ], [
                'name' => 'Pump Starter Fault [10]',
                'machine_id' => MACHINE_VTC_PLUS_CONVEYING_SYSTEM,
                'tag_id' => 20,
                'offset' => 9,
                'bytes' => 1
            ], [
                'name' => 'Pump Starter Fault [11]',
                'machine_id' => MACHINE_VTC_PLUS_CONVEYING_SYSTEM,
                'tag_id' => 20,
                'offset' => 10,
                'bytes' => 1
            ], [
                'name' => 'Pump Starter Fault [12]',
                'machine_id' => MACHINE_VTC_PLUS_CONVEYING_SYSTEM,
                'tag_id' => 20,
                'offset' => 11,
                'bytes' => 1
            ], [
                'name' => 'Power Loss While Running',
                'machine_id' => MACHINE_VTC_PLUS_CONVEYING_SYSTEM,
                'tag_id' => 19,
                'offset' => 0,
                'bytes' => 1
            ], [
                'name' => 'Vacuum Levels Option [1]',
                'machine_id' => MACHINE_VTC_PLUS_CONVEYING_SYSTEM,
                'tag_id' => 21,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'Vacuum Levels Option [2]',
                'machine_id' => MACHINE_VTC_PLUS_CONVEYING_SYSTEM,
                'tag_id' => 21,
                'offset' => 1,
                'bytes' => 0
            ], [
                'name' => 'Vacuum Levels Option [3]',
                'machine_id' => MACHINE_VTC_PLUS_CONVEYING_SYSTEM,
                'tag_id' => 21,
                'offset' => 2,
                'bytes' => 0
            ], [
                'name' => 'Vacuum Levels Option [4]',
                'machine_id' => MACHINE_VTC_PLUS_CONVEYING_SYSTEM,
                'tag_id' => 21,
                'offset' => 3,
                'bytes' => 0
            ], [
                'name' => 'Vacuum Levels Option [5]',
                'machine_id' => MACHINE_VTC_PLUS_CONVEYING_SYSTEM,
                'tag_id' => 21,
                'offset' => 4,
                'bytes' => 0
            ], [
                'name' => 'Vacuum Levels Option [6]',
                'machine_id' => MACHINE_VTC_PLUS_CONVEYING_SYSTEM,
                'tag_id' => 21,
                'offset' => 5,
                'bytes' => 0
            ], [
                'name' => 'Vacuum Levels Option [7]',
                'machine_id' => MACHINE_VTC_PLUS_CONVEYING_SYSTEM,
                'tag_id' => 21,
                'offset' => 6,
                'bytes' => 0
            ], [
                'name' => 'Vacuum Levels Option [8]',
                'machine_id' => MACHINE_VTC_PLUS_CONVEYING_SYSTEM,
                'tag_id' => 21,
                'offset' => 7,
                'bytes' => 0
            ], [
                'name' => 'Vacuum Levels Option [9]',
                'machine_id' => MACHINE_VTC_PLUS_CONVEYING_SYSTEM,
                'tag_id' => 21,
                'offset' => 8,
                'bytes' => 0
            ], [
                'name' => 'Vacuum Levels Option [10]',
                'machine_id' => MACHINE_VTC_PLUS_CONVEYING_SYSTEM,
                'tag_id' => 21,
                'offset' => 9,
                'bytes' => 0
            ], [
                'name' => 'Vacuum Levels Option [11]',
                'machine_id' => MACHINE_VTC_PLUS_CONVEYING_SYSTEM,
                'tag_id' => 21,
                'offset' => 10,
                'bytes' => 0
            ], [
                'name' => 'Vacuum Levels Option [12]',
                'machine_id' => MACHINE_VTC_PLUS_CONVEYING_SYSTEM,
                'tag_id' => 21,
                'offset' => 11,
                'bytes' => 0
            ], [
                'name' => 'Dirty Filter Pressure Switch Warning',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 60,
                'offset' => 0,
                'bytes' => 1
            ], [
                'name' => 'Dew Point Sensor Failure Fault',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 60,
                'offset' => 1,
                'bytes' => 1
            ], [
                'name' => 'High Dew Point Warning',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 60,
                'offset' => 2,
                'bytes' => 1
            ], [
                'name' => 'Process Blower Rotation Failure Fault',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 60,
                'offset' => 3,
                'bytes' => 1
            ], [
                'name' => 'DH1 Process Low Temperature Warning',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 60,
                'offset' => 4,
                'bytes' => 1
            ], [
                'name' => 'DH1 Process High Temperature Fault',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 60,
                'offset' => 5,
                'bytes' => 1
            ], [
                'name' => 'DH1 Process Temp Loop Break Fault',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 60,
                'offset' => 6,
                'bytes' => 1
            ], [
                'name' => 'DH1 Process Return Temp Sensor Failure Fault',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 60,
                'offset' => 7,
                'bytes' => 1
            ], [
                'name' => 'DH1 Process Supply Temp Sensor Failure Fault',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 60,
                'offset' => 8,
                'bytes' => 1
            ], [
                'name' => 'DH1 Process Heater Safety Fault',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 60,
                'offset' => 9,
                'bytes' => 1
            ], [
                'name' => 'Regen Exhaust Temp Sensor Failure Fault',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 60,
                'offset' => 10,
                'bytes' => 1
            ], [
                'name' => 'Regen Left Bed Heater Temp Sensor Failure Fault',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 60,
                'offset' => 11,
                'bytes' => 1
            ], [
                'name' => 'Regen Low air Temperature Fault',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 60,
                'offset' => 12,
                'bytes' => 1
            ], [
                'name' => 'Regen Right Bed Heater Temp Sensor Failure Fault',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 60,
                'offset' => 13,
                'bytes' => 1
            ], [
                'name' => 'Regen Heater Safety Fault',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 60,
                'offset' => 14,
                'bytes' => 1
            ], [
                'name' => 'Process Air Valve Position Fault',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 60,
                'offset' => 15,
                'bytes' => 1
            ], [
                'name' => 'Process Blower Overload Fault',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 60,
                'offset' => 16,
                'bytes' => 1
            ], [
                'name' => 'DH2 Process High Temperature Fault',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 60,
                'offset' => 17,
                'bytes' => 1
            ], [
                'name' => 'DH3 Process High Temperature Fault',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 60,
                'offset' => 18,
                'bytes' => 1
            ], [
                'name' => 'DH2 Process Supply Temp Sensor Failure Fault',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 60,
                'offset' => 19,
                'bytes' => 1
            ], [
                'name' => 'DH3 Process Supply Temp Sensor Failure Fault',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 60,
                'offset' => 20,
                'bytes' => 1
            ], [
                'name' => 'DH2 Process Heater Safety Fault',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 60,
                'offset' => 21,
                'bytes' => 1
            ], [
                'name' => 'DH3 Process Heater Safety Fault',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 60,
                'offset' => 22,
                'bytes' => 1
            ], [
                'name' => 'DH2 Process Temp Loop Break Fault',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 60,
                'offset' => 23,
                'bytes' => 1
            ], [
                'name' => 'DH3 Process Temp Loop Break Fault',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 60,
                'offset' => 24,
                'bytes' => 1
            ], [
                'name' => 'DH2 Process Return Temp Sensor Failure Fault',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 60,
                'offset' => 25,
                'bytes' => 1
            ], [
                'name' => 'DH3 Process Return Temp Sensor Failure Fault',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 60,
                'offset' => 26,
                'bytes' => 1
            ], [
                'name' => 'Regen Heater High Temp Fault',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 60,
                'offset' => 27,
                'bytes' => 1
            ], [
                'name' => 'Bed Break Temperature Not Reached Warning',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 60,
                'offset' => 28,
                'bytes' => 1
            ], [
                'name' => 'Regen Exhaust High - Cooling Extended Warning',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 60,
                'offset' => 29,
                'bytes' => 1
            ], [
                'name' => 'Regen Cooling Taking Too Long Fault ',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 60,
                'offset' => 30,
                'bytes' => 1
            ], [
                'name' => 'High Dew Point Alarm Fault',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 61,
                'offset' => 0,
                'bytes' => 1
            ], [
                'name' => 'Regen Air Valve Position Fault',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 61,
                'offset' => 1,
                'bytes' => 1
            ], [
                'name' => 'E-Stop Fault',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 61,
                'offset' => 2,
                'bytes' => 1
            ], [
                'name' => 'PLC Expansion Card Error - Slot 1 Fault',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 61,
                'offset' => 3,
                'bytes' => 1
            ], [
                'name' => 'PLC Expansion Card Error - Slot 2 Fault',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 61,
                'offset' => 4,
                'bytes' => 1
            ], [
                'name' => 'Return Air Over Temperature Fault',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 61,
                'offset' => 5,
                'bytes' => 1
            ], [
                'name' => 'DH2 Process Low Temperature Warning',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 61,
                'offset' => 6,
                'bytes' => 1
            ], [
                'name' => 'DH3 Process Low Temperature Warning',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 61,
                'offset' => 7,
                'bytes' => 1
            ], [
                'name' => 'PLC Expansion Card Error - Slot 3 Fault',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 61,
                'offset' => 8,
                'bytes' => 1
            ], [
                'name' => 'Mach Loader MCP tripped',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 61,
                'offset' => 10,
                'bytes' => 1
            ], [
                'name' => 'Mach Loader Aux run contact',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 61,
                'offset' => 11,
                'bytes' => 1
            ], [
                'name' => 'Mach Loader 1 no convey',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 61,
                'offset' => 12,
                'bytes' => 1
            ], [
                'name' => 'Mach Loader 2 no convey',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 61,
                'offset' => 13,
                'bytes' => 1
            ], [
                'name' => 'Hopper Loader MCP tripped',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 61,
                'offset' => 14,
                'bytes' => 1
            ], [
                'name' => 'Hopper Loader Aux run contact',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 61,
                'offset' => 15,
                'bytes' => 1
            ], [
                'name' => 'Hopper Loader no convey',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 61,
                'offset' => 16,
                'bytes' => 1
            ], [
                'name' => 'Regen Test Heater Fail',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 61,
                'offset' => 17,
                'bytes' => 1
            ], [
                'name' => 'Regen Test Ended - Fail',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 61,
                'offset' => 18,
                'bytes' => 1
            ], [
                'name' => 'Regen Test Ended - Pass',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 61,
                'offset' => 19,
                'bytes' => 1
            ], [
                'name' => 'After-Cooler T/C failure',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 61,
                'offset' => 20,
                'bytes' => 1
            ], [
                'name' => 'After-Cooler high temperature warning',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 61,
                'offset' => 21,
                'bytes' => 1
            ], [
                'name' => 'After-Cooler high temperature shutdown',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 61,
                'offset' => 22,
                'bytes' => 1
            ], [
                'name' => 'DH1 Return Air high temp Warning',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 61,
                'offset' => 23,
                'bytes' => 1
            ], [
                'name' => 'DH1 Return Air high temp shutdown',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 61,
                'offset' => 24,
                'bytes' => 1
            ], [
                'name' => 'DH2 Return Air high temp Warning',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 61,
                'offset' => 25,
                'bytes' => 1
            ], [
                'name' => 'DH2 Return Air high temp shutdown',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 61,
                'offset' => 26,
                'bytes' => 1
            ], [
                'name' => 'DH3 Return Air high temp Warning',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 61,
                'offset' => 27,
                'bytes' => 1
            ], [
                'name' => 'DH3 Return Air high temp shutdown',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 61,
                'offset' => 28,
                'bytes' => 1
            ], [
                'name' => 'Low Temperature Selected with no Pre-Cooler',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 61,
                'offset' => 29,
                'bytes' => 1
            ], [
                'name' => 'High Temperature Selected with no After-Cooler',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 61,
                'offset' => 30,
                'bytes' => 1
            ], [
                'name' => 'Dew Point Sensor Failure Warning',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 62,
                'offset' => 0,
                'bytes' => 1
            ], [
                'name' => 'Regen Exhaust Sensor Failure Warning',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 62,
                'offset' => 1,
                'bytes' => 1
            ], [
                'name' => 'PLC Clock Error Warning',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 62,
                'offset' => 2,
                'bytes' => 1
            ], [
                'name' => 'Power On Start',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 62,
                'offset' => 3,
                'bytes' => 1
            ], [
                'name' => 'Auto Start',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 62,
                'offset' => 4,
                'bytes' => 1
            ], [
                'name' => 'Auto Stop',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 62,
                'offset' => 5,
                'bytes' => 1
            ], [
                'name' => 'PLC initialize',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 62,
                'offset' => 6,
                'bytes' => 1
            ], [
                'name' => 'DH1 - Pressure switch verification fail',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 62,
                'offset' => 7,
                'bytes' => 1
            ], [
                'name' => 'DH2 - Pressure switch verification fail',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 62,
                'offset' => 8,
                'bytes' => 1
            ], [
                'name' => 'DH3 - Pressure switch verification fail',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 62,
                'offset' => 9,
                'bytes' => 1
            ], [
                'name' => 'Regen Test Fail',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 62,
                'offset' => 10,
                'bytes' => 1
            ], [
                'name' => 'Blower Hours Maintenance',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 62,
                'offset' => 16,
                'bytes' => 1
            ], [
                'name' => 'Drying Hopper 1 Heater Hours Maintenance',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 62,
                'offset' => 17,
                'bytes' => 1
            ], [
                'name' => 'Drying Hopper 2 Heater Hours Maintenance',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 62,
                'offset' => 18,
                'bytes' => 1
            ], [
                'name' => 'Drying Hopper 3 Heater Hours Maintenance',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 62,
                'offset' => 19,
                'bytes' => 1
            ], [
                'name' => 'Left Regen Heater Hours Maintenance',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 62,
                'offset' => 20,
                'bytes' => 1
            ], [
                'name' => 'Right Regen Heater Hours Maintenance',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 62,
                'offset' => 21,
                'bytes' => 1
            ], [
                'name' => 'Filter Hours Maintenance',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 62,
                'offset' => 22,
                'bytes' => 1
            ], [
                'name' => 'Dryer Online Hours Maintenance',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 62,
                'offset' => 23,
                'bytes' => 1
            ], [
                'name' => 'Hopper Loader Blower Hours Maintenance',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 62,
                'offset' => 24,
                'bytes' => 1
            ], [
                'name' => 'Machine Loader Blower Hours Maintenance',
                'machine_id' => MACHINE_NGX_DRYER,
                'tag_id' => 62,
                'offset' => 25,
                'bytes' => 1
            ], [
                'name' => 'Dirty Filter Pressure Switch Warning',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 46,
                'offset' => 0,
                'bytes' => 1
            ], [
                'name' => 'Dew Point Sensor Failure Fault',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 46,
                'offset' => 1,
                'bytes' => 1
            ], [
                'name' => 'High Dew Point Warning',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 46,
                'offset' => 2,
                'bytes' => 1
            ], [
                'name' => 'Process Blower Rotation Failure Fault',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 46,
                'offset' => 3,
                'bytes' => 1
            ], [
                'name' => 'DH1 Process Low Temperature Warning',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 46,
                'offset' => 4,
                'bytes' => 1
            ], [
                'name' => 'DH1 Process High Temperature Fault',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 46,
                'offset' => 5,
                'bytes' => 1
            ], [
                'name' => 'DH1 Process Temp Loop Break Fault',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 46,
                'offset' => 6,
                'bytes' => 1
            ], [
                'name' => 'DH1 Process Return Temp Sensor Failure Fault',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 46,
                'offset' => 7,
                'bytes' => 1
            ], [
                'name' => 'DH1 Process Supply Temp Sensor Failure Fault',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 46,
                'offset' => 8,
                'bytes' => 1
            ], [
                'name' => 'DH1 Process Heater Safety Fault',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 46,
                'offset' => 9,
                'bytes' => 1
            ], [
                'name' => 'Regen Exhaust Temp Sensor Failure Fault',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 46,
                'offset' => 10,
                'bytes' => 1
            ], [
                'name' => 'Regen Left Bed Heater Temp Sensor Failure Fault',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 46,
                'offset' => 11,
                'bytes' => 1
            ], [
                'name' => 'Regen Low air Temperature Fault',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 46,
                'offset' => 12,
                'bytes' => 1
            ], [
                'name' => 'Regen Right Bed Heater Temp Sensor Failure Fault',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 46,
                'offset' => 13,
                'bytes' => 1
            ], [
                'name' => 'Regen Heater Safety Fault',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 46,
                'offset' => 14,
                'bytes' => 1
            ], [
                'name' => 'Process Air Valve Position Fault',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 46,
                'offset' => 15,
                'bytes' => 1
            ], [
                'name' => 'Process Blower Overload Fault',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 46,
                'offset' => 16,
                'bytes' => 1
            ], [
                'name' => 'DH2 Process High Temperature Fault',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 46,
                'offset' => 17,
                'bytes' => 1
            ], [
                'name' => 'DH3 Process High Temperature Fault',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 46,
                'offset' => 18,
                'bytes' => 1
            ], [
                'name' => 'DH2 Process Supply Temp Sensor Failure Fault',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 46,
                'offset' => 19,
                'bytes' => 1
            ], [
                'name' => 'DH3 Process Supply Temp Sensor Failure Fault',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 46,
                'offset' => 20,
                'bytes' => 1
            ], [
                'name' => 'DH2 Process Heater Safety Fault',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 46,
                'offset' => 21,
                'bytes' => 1
            ], [
                'name' => 'DH3 Process Heater Safety Fault',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 46,
                'offset' => 22,
                'bytes' => 1
            ], [
                'name' => 'DH2 Process Temp Loop Break Fault',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 46,
                'offset' => 23,
                'bytes' => 1
            ], [
                'name' => 'DH3 Process Temp Loop Break Fault',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 46,
                'offset' => 24,
                'bytes' => 1
            ], [
                'name' => 'DH2 Process Return Temp Sensor Failure Fault',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 46,
                'offset' => 25,
                'bytes' => 1
            ], [
                'name' => 'DH3 Process Return Temp Sensor Failure Fault',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 46,
                'offset' => 26,
                'bytes' => 1
            ], [
                'name' => 'Regen Heater High Temp Fault',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 46,
                'offset' => 27,
                'bytes' => 1
            ], [
                'name' => 'Bed Break Temperature Not Reached Warning',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 46,
                'offset' => 28,
                'bytes' => 1
            ], [
                'name' => 'Regen Exhaust High - Cooling Extended Warning',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 46,
                'offset' => 29,
                'bytes' => 1
            ], [
                'name' => 'Regen Cooling Taking Too Long Fault',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 46,
                'offset' => 30,
                'bytes' => 1
            ], [
                'name' => 'High Dew Point Alarm Fault',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 47,
                'offset' => 0,
                'bytes' => 1
            ], [
                'name' => 'Regen Air Valve Position Fault',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 47,
                'offset' => 1,
                'bytes' => 1
            ], [
                'name' => 'E-Stop Fault',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 47,
                'offset' => 2,
                'bytes' => 1
            ], [
                'name' => 'PLC Expansion Card Error - Slot 1 Fault',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 47,
                'offset' => 3,
                'bytes' => 1
            ], [
                'name' => 'PLC Expansion Card Error - Slot 2 Fault',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 47,
                'offset' => 4,
                'bytes' => 1
            ], [
                'name' => 'Return Air Over Temperature Fault',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 47,
                'offset' => 5,
                'bytes' => 1
            ], [
                'name' => 'DH2 Process Low Temperature Warning',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 47,
                'offset' => 6,
                'bytes' => 1
            ], [
                'name' => 'DH3 Process Low Temperature Warning',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 47,
                'offset' => 7,
                'bytes' => 1
            ], [
                'name' => 'PLC Expansion Card Error - Slot 3 Fault',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 47,
                'offset' => 8,
                'bytes' => 1
            ], [
                'name' => 'Mach Loader MCP tripped',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 47,
                'offset' => 10,
                'bytes' => 1
            ], [
                'name' => 'Mach Loader Aux run contact',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 47,
                'offset' => 11,
                'bytes' => 1
            ], [
                'name' => 'Mach Loader 1 no convey',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 47,
                'offset' => 12,
                'bytes' => 1
            ], [
                'name' => 'Mach Loader 2 no convey',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 47,
                'offset' => 13,
                'bytes' => 1
            ], [
                'name' => 'Hopper Loader MCP tripped',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 47,
                'offset' => 14,
                'bytes' => 1
            ], [
                'name' => 'Hopper Loader Aux run contact',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 47,
                'offset' => 15,
                'bytes' => 1
            ], [
                'name' => 'Hopper Loader no convey',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 47,
                'offset' => 16,
                'bytes' => 1
            ], [
                'name' => 'Regen Test Heater Fail',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 47,
                'offset' => 17,
                'bytes' => 1
            ], [
                'name' => 'Regen Test Ended - Fail',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 47,
                'offset' => 18,
                'bytes' => 1
            ], [
                'name' => 'Regen Test Ended - Pass',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 47,
                'offset' => 19,
                'bytes' => 1
            ], [
                'name' => 'After-Cooler T/C failure',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 47,
                'offset' => 20,
                'bytes' => 1
            ], [
                'name' => 'After-Cooler high temperature warning',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 47,
                'offset' => 21,
                'bytes' => 1
            ], [
                'name' => 'After-Cooler high temperature shutdown',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 47,
                'offset' => 22,
                'bytes' => 1
            ], [
                'name' => 'DH1 Return Air high temp Warning',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 47,
                'offset' => 23,
                'bytes' => 1
            ], [
                'name' => 'DH1 Return Air high temp shutdown',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 47,
                'offset' => 24,
                'bytes' => 1
            ], [
                'name' => 'DH2 Return Air high temp Warning',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 47,
                'offset' => 25,
                'bytes' => 1
            ], [
                'name' => 'DH2 Return Air high temp shutdown',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 47,
                'offset' => 26,
                'bytes' => 1
            ], [
                'name' => 'DH3 Return Air high temp Warning',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 47,
                'offset' => 27,
                'bytes' => 1
            ], [
                'name' => 'DH3 Return Air high temp shutdown',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 47,
                'offset' => 28,
                'bytes' => 1
            ], [
                'name' => 'Low Temperature Selected with no Pre-Cooler',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 47,
                'offset' => 29,
                'bytes' => 1
            ], [
                'name' => 'High Temperature Selected with no After-Cooler',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 47,
                'offset' => 30,
                'bytes' => 1
            ], [
                'name' => 'Dew Point Sensor Failure Warning',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 48,
                'offset' => 0,
                'bytes' => 1
            ], [
                'name' => 'Regen Exhaust Sensor Failure Warning',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 48,
                'offset' => 1,
                'bytes' => 1
            ], [
                'name' => 'PLC Clock Error Warning',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 48,
                'offset' => 2,
                'bytes' => 1
            ], [
                'name' => 'Power On Start',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 48,
                'offset' => 3,
                'bytes' => 1
            ], [
                'name' => 'Auto Start',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 48,
                'offset' => 4,
                'bytes' => 1
            ], [
                'name' => 'Auto Stop',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 48,
                'offset' => 5,
                'bytes' => 1
            ], [
                'name' => 'PLC initialize',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 48,
                'offset' => 6,
                'bytes' => 1
            ], [
                'name' => 'DH1 - Pressure switch verification fail',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 48,
                'offset' => 7,
                'bytes' => 1
            ], [
                'name' => 'DH2 - Pressure switch verification fail',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 48,
                'offset' => 8,
                'bytes' => 1
            ], [
                'name' => 'DH3 - Pressure switch verification fail',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 48,
                'offset' => 9,
                'bytes' => 1
            ], [
                'name' => 'Regen Test Fail',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 48,
                'offset' => 10,
                'bytes' => 1
            ], [
                'name' => 'Blower Hours Maintenance',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 48,
                'offset' => 16,
                'bytes' => 1
            ], [
                'name' => 'Drying Hopper 1 Heater Hours Maintenance',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 48,
                'offset' => 17,
                'bytes' => 1
            ], [
                'name' => 'Drying Hopper 2 Heater Hours Maintenance',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 48,
                'offset' => 18,
                'bytes' => 1
            ], [
                'name' => 'Drying Hopper 3 Heater Hours Maintenance',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 48,
                'offset' => 19,
                'bytes' => 1
            ], [
                'name' => 'Left Regen Heater Hours Maintenance',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 48,
                'offset' => 20,
                'bytes' => 1
            ], [
                'name' => 'Right Regen Heater Hours Maintenance',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 48,
                'offset' => 21,
                'bytes' => 1
            ], [
                'name' => 'Filter Hours Maintenance',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 48,
                'offset' => 22,
                'bytes' => 1
            ], [
                'name' => 'Dryer Online Hours Maintenance',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 48,
                'offset' => 23,
                'bytes' => 1
            ], [
                'name' => 'Hopper Loader Blower Hours Maintenance',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 48,
                'offset' => 24,
                'bytes' => 1
            ], [
                'name' => 'Machine Loader Blower Hours Maintenance',
                'machine_id' => MACHINE_NGX_NOMAD_DRYER,
                'tag_id' => 48,
                'offset' => 25,
                'bytes' => 1
            ], [
                'name' => 'E-Stop Pressed',
                'machine_id' => MACHINE_T50_CENTRAL_GRANULATOR,
                'tag_id' => 47,
                'offset' => 0,
                'bytes' => 1
            ], [
                'name' => '[Aux 1] Fault',
                'machine_id' => MACHINE_T50_CENTRAL_GRANULATOR,
                'tag_id' => 47,
                'offset' => 1,
                'bytes' => 1
            ], [
                'name' => '[Aux 1] Overload Fault',
                'machine_id' => MACHINE_T50_CENTRAL_GRANULATOR,
                'tag_id' => 47,
                'offset' => 2,
                'bytes' => 1
            ], [
                'name' => '[Conveyor] Fault',
                'machine_id' => MACHINE_T50_CENTRAL_GRANULATOR,
                'tag_id' => 47,
                'offset' => 3,
                'bytes' => 1
            ], [
                'name' => '[Conveyor] Overload Fault',
                'machine_id' => MACHINE_T50_CENTRAL_GRANULATOR,
                'tag_id' => 47,
                'offset' => 4,
                'bytes' => 1
            ], [
                'name' => 'Granulator Fault',
                'machine_id' => MACHINE_T50_CENTRAL_GRANULATOR,
                'tag_id' => 47,
                'offset' => 5,
                'bytes' => 1
            ], [
                'name' => 'Granulator Overload Fault',
                'machine_id' => MACHINE_T50_CENTRAL_GRANULATOR,
                'tag_id' => 47,
                'offset' => 6,
                'bytes' => 1
            ], [
                'name' => '[Blower] Fault',
                'machine_id' => MACHINE_T50_CENTRAL_GRANULATOR,
                'tag_id' => 47,
                'offset' => 7,
                'bytes' => 1
            ], [
                'name' => '[Blower] Overload Fault',
                'machine_id' => MACHINE_T50_CENTRAL_GRANULATOR,
                'tag_id' => 47,
                'offset' => 8,
                'bytes' => 1
            ], [
                'name' => '[Aux 2] Fault',
                'machine_id' => MACHINE_T50_CENTRAL_GRANULATOR,
                'tag_id' => 47,
                'offset' => 9,
                'bytes' => 1
            ], [
                'name' => '[Aux 2] Overload Fault',
                'machine_id' => MACHINE_T50_CENTRAL_GRANULATOR,
                'tag_id' => 47,
                'offset' => 10,
                'bytes' => 1
            ], [
                'name' => 'Bearing 1 Temp Sensor Warning',
                'machine_id' => MACHINE_T50_CENTRAL_GRANULATOR,
                'tag_id' => 47,
                'offset' => 11,
                'bytes' => 1
            ], [
                'name' => 'Bearing 2 Temp Sensor Warning',
                'machine_id' => MACHINE_T50_CENTRAL_GRANULATOR,
                'tag_id' => 47,
                'offset' => 12,
                'bytes' => 1
            ], [
                'name' => 'Granulator Amps Sensor Warning',
                'machine_id' => MACHINE_T50_CENTRAL_GRANULATOR,
                'tag_id' => 47,
                'offset' => 13,
                'bytes' => 1
            ], [
                'name' => 'Bearing 1 High Temp Warning',
                'machine_id' => MACHINE_T50_CENTRAL_GRANULATOR,
                'tag_id' => 47,
                'offset' => 14,
                'bytes' => 1
            ], [
                'name' => 'Bearing 2 High Temp Warning',
                'machine_id' => MACHINE_T50_CENTRAL_GRANULATOR,
                'tag_id' => 47,
                'offset' => 15,
                'bytes' => 1
            ], [
                'name' => 'Discharge Bin Not In Place',
                'machine_id' => MACHINE_T50_CENTRAL_GRANULATOR,
                'tag_id' => 47,
                'offset' => 16,
                'bytes' => 1
            ], [
                'name' => 'Rear Access Door Open',
                'machine_id' => MACHINE_T50_CENTRAL_GRANULATOR,
                'tag_id' => 47,
                'offset' => 17,
                'bytes' => 1
            ], [
                'name' => 'Front Access Door Open',
                'machine_id' => MACHINE_T50_CENTRAL_GRANULATOR,
                'tag_id' => 47,
                'offset' => 18,
                'bytes' => 1
            ], [
                'name' => 'Hopper Access Door Open',
                'machine_id' => MACHINE_T50_CENTRAL_GRANULATOR,
                'tag_id' => 47,
                'offset' => 19,
                'bytes' => 1
            ], [
                'name' => 'Rotor Lock Not Retracted',
                'machine_id' => MACHINE_T50_CENTRAL_GRANULATOR,
                'tag_id' => 47,
                'offset' => 20,
                'bytes' => 1
            ], [
                'name' => 'Main Power Not On',
                'machine_id' => MACHINE_T50_CENTRAL_GRANULATOR,
                'tag_id' => 47,
                'offset' => 21,
                'bytes' => 1
            ], [
                'name' => 'Safety Relay output off',
                'machine_id' => MACHINE_T50_CENTRAL_GRANULATOR,
                'tag_id' => 47,
                'offset' => 22,
                'bytes' => 1
            ], [
                'name' => 'Granulator High Current',
                'machine_id' => MACHINE_T50_CENTRAL_GRANULATOR,
                'tag_id' => 47,
                'offset' => 23,
                'bytes' => 1
            ], [
                'name' => 'Bin Full Alarm',
                'machine_id' => MACHINE_T50_CENTRAL_GRANULATOR,
                'tag_id' => 47,
                'offset' => 24,
                'bytes' => 1
            ], [
                'name' => 'Idle - System Shutdown',
                'machine_id' => MACHINE_T50_CENTRAL_GRANULATOR,
                'tag_id' => 48,
                'offset' => 0,
                'bytes' => 1
            ], [
                'name' => 'Bin Full Convey Inhibit',
                'machine_id' => MACHINE_T50_CENTRAL_GRANULATOR,
                'tag_id' => 48,
                'offset' => 1,
                'bytes' => 1
            ], [
                'name' => 'Automatic Run Maintenance Reminder',
                'machine_id' => MACHINE_T50_CENTRAL_GRANULATOR,
                'tag_id' => 48,
                'offset' => 2,
                'bytes' => 1
            ], [
                'name' => 'Granulator Motor Maintenance Reminder',
                'machine_id' => MACHINE_T50_CENTRAL_GRANULATOR,
                'tag_id' => 48,
                'offset' => 3,
                'bytes' => 1
            ], [
                'name' => '[Blower] Motor Maintenance Reminder',
                'machine_id' => MACHINE_T50_CENTRAL_GRANULATOR,
                'tag_id' => 48,
                'offset' => 4,
                'bytes' => 1
            ], [
                'name' => '[Conveyor] Motor Maintenance Reminder',
                'machine_id' => MACHINE_T50_CENTRAL_GRANULATOR,
                'tag_id' => 48,
                'offset' => 5,
                'bytes' => 1
            ], [
                'name' => '[Aux 1] Motor Maintenance Reminder',
                'machine_id' => MACHINE_T50_CENTRAL_GRANULATOR,
                'tag_id' => 48,
                'offset' => 6,
                'bytes' => 1
            ], [
                'name' => '[Aux 2] Motor Maintenance Reminder',
                'machine_id' => MACHINE_T50_CENTRAL_GRANULATOR,
                'tag_id' => 48,
                'offset' => 7,
                'bytes' => 1
            ], [
                'name' => 'PLC Initialized',
                'machine_id' => MACHINE_T50_CENTRAL_GRANULATOR,
                'tag_id' => 48,
                'offset' => 8,
                'bytes' => 1
            ], [
                'name' => 'No flow alarm for pump 1',
                'machine_id' => MACHINE_GP_PORTABLE_CHILLER,
                'tag_id' => 26,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'High temperature warning alarm',
                'machine_id' => MACHINE_GP_PORTABLE_CHILLER,
                'tag_id' => 27,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'High temperature fault alarm',
                'machine_id' => MACHINE_GP_PORTABLE_CHILLER,
                'tag_id' => 28,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'Low temperature warning alarm',
                'machine_id' => MACHINE_GP_PORTABLE_CHILLER,
                'tag_id' => 29,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'Low temperature fault alarm',
                'machine_id' => MACHINE_GP_PORTABLE_CHILLER,
                'tag_id' => 30,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'Compressor fault alarm',
                'machine_id' => MACHINE_GP_PORTABLE_CHILLER,
                'tag_id' => 31,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'Low suction pressure at startup alarm',
                'machine_id' => MACHINE_GP_PORTABLE_CHILLER,
                'tag_id' => 32,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'Low suction pressure warning alarm',
                'machine_id' => MACHINE_GP_PORTABLE_CHILLER,
                'tag_id' => 33,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'Low suction pressure fault alarm',
                'machine_id' => MACHINE_GP_PORTABLE_CHILLER,
                'tag_id' => 34,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'High suction pressure warning alarm',
                'machine_id' => MACHINE_GP_PORTABLE_CHILLER,
                'tag_id' => 35,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'High suction pressure fault alarm',
                'machine_id' => MACHINE_GP_PORTABLE_CHILLER,
                'tag_id' => 36,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'Low discharge pressure warning alarm',
                'machine_id' => MACHINE_GP_PORTABLE_CHILLER,
                'tag_id' => 37,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'Low discharge pressure fault alarm',
                'machine_id' => MACHINE_GP_PORTABLE_CHILLER,
                'tag_id' => 38,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'High discharge pressure warning alarm',
                'machine_id' => MACHINE_GP_PORTABLE_CHILLER,
                'tag_id' => 39,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'High discharge pressure fault alarm',
                'machine_id' => MACHINE_GP_PORTABLE_CHILLER,
                'tag_id' => 40,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'Low tank level warning alarm',
                'machine_id' => MACHINE_GP_PORTABLE_CHILLER,
                'tag_id' => 41,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'Low tank level fault alarm',
                'machine_id' => MACHINE_GP_PORTABLE_CHILLER,
                'tag_id' => 42,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'High tank level warning alarm',
                'machine_id' => MACHINE_GP_PORTABLE_CHILLER,
                'tag_id' => 43,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'High tank level fault alarm',
                'machine_id' => MACHINE_GP_PORTABLE_CHILLER,
                'tag_id' => 44,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'High discharge pressure switch fault',
                'machine_id' => MACHINE_GP_PORTABLE_CHILLER,
                'tag_id' => 45,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'Pump 1 overload alarm',
                'machine_id' => MACHINE_GP_PORTABLE_CHILLER,
                'tag_id' => 46,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'Condenser fan overload alarm',
                'machine_id' => MACHINE_GP_PORTABLE_CHILLER,
                'tag_id' => 47,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'Chiller out temperature sensor failure',
                'machine_id' => MACHINE_GP_PORTABLE_CHILLER,
                'tag_id' => 48,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'Chiller in temperature sensor failure',
                'machine_id' => MACHINE_GP_PORTABLE_CHILLER,
                'tag_id' => 49,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'Suction pressure sensor failure',
                'machine_id' => MACHINE_GP_PORTABLE_CHILLER,
                'tag_id' => 50,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'Discharge pressure sensor failure',
                'machine_id' => MACHINE_GP_PORTABLE_CHILLER,
                'tag_id' => 51,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'Refrigerant suction temperature sensor failure',
                'machine_id' => MACHINE_GP_PORTABLE_CHILLER,
                'tag_id' => 52,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'Condenser inlet temperature sensro failure',
                'machine_id' => MACHINE_GP_PORTABLE_CHILLER,
                'tag_id' => 53,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'Refrigerant liquid temperature sensor failure',
                'machine_id' => MACHINE_GP_PORTABLE_CHILLER,
                'tag_id' => 54,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'Tank level sensor failure',
                'machine_id' => MACHINE_GP_PORTABLE_CHILLER,
                'tag_id' => 55,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'Pump pressure sensor failure',
                'machine_id' => MACHINE_GP_PORTABLE_CHILLER,
                'tag_id' => 56,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'Pump flow sensor failure',
                'machine_id' => MACHINE_GP_PORTABLE_CHILLER,
                'tag_id' => 57,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'Low superheat fault',
                'machine_id' => MACHINE_GP_PORTABLE_CHILLER,
                'tag_id' => 58,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'Auxiliary alarm',
                'machine_id' => MACHINE_GP_PORTABLE_CHILLER,
                'tag_id' => 59,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'Pump down override fault',
                'machine_id' => MACHINE_GP_PORTABLE_CHILLER,
                'tag_id' => 60,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'Condenser differential pressure warning',
                'machine_id' => MACHINE_GP_PORTABLE_CHILLER,
                'tag_id' => 61,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'Evaporator differential pressure warning',
                'machine_id' => MACHINE_GP_PORTABLE_CHILLER,
                'tag_id' => 62,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'Compressor differential pressure fault',
                'machine_id' => MACHINE_GP_PORTABLE_CHILLER,
                'tag_id' => 63,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'Compressor differential pressure warning',
                'machine_id' => MACHINE_GP_PORTABLE_CHILLER,
                'tag_id' => 64,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'Tank fill rate of change fault',
                'machine_id' => MACHINE_GP_PORTABLE_CHILLER,
                'tag_id' => 65,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'Phase monitor alarm',
                'machine_id' => MACHINE_GP_PORTABLE_CHILLER,
                'tag_id' => 66,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'High temperature switch fault',
                'machine_id' => MACHINE_GP_PORTABLE_CHILLER,
                'tag_id' => 67,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'To process temperature sensor failure',
                'machine_id' => MACHINE_GP_PORTABLE_CHILLER,
                'tag_id' => 68,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'From process temperature sensor failure',
                'machine_id' => MACHINE_GP_PORTABLE_CHILLER,
                'tag_id' => 69,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'Process high temperature fault',
                'machine_id' => MACHINE_GP_PORTABLE_CHILLER,
                'tag_id' => 70,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'Process low temperature fault',
                'machine_id' => MACHINE_GP_PORTABLE_CHILLER,
                'tag_id' => 71,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'Condenser fan VFD fault',
                'machine_id' => MACHINE_GP_PORTABLE_CHILLER,
                'tag_id' => 72,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'Process high temperature warning',
                'machine_id' => MACHINE_GP_PORTABLE_CHILLER,
                'tag_id' => 73,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'Process low temperature warning',
                'machine_id' => MACHINE_GP_PORTABLE_CHILLER,
                'tag_id' => 74,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'Pump 2 no flow fault',
                'machine_id' => MACHINE_GP_PORTABLE_CHILLER,
                'tag_id' => 75,
                'offset' => 0,
                'bytes' => 0
            ], [
                'name' => 'Pump 2 overload fault',
                'machine_id' => MACHINE_GP_PORTABLE_CHILLER,
                'tag_id' => 76,
                'offset' => 0,
                'bytes' => 0
            ], 

            [
                'name' => 'Circuit 1 - Ambient Temperature Sensor Faiure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 163,
                'offset' => 0,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 1 - Aux Alarm Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 163,
                'offset' => 1,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 1 - Aux Alarm Warning',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 163,
                'offset' => 2,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 1 - Chill In Temperature Sensor Failure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 163,
                'offset' => 3,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 1 - Chill Out Temperature Sensor Failure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 163,
                'offset' => 4,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 1 - Chilled Water Dirty Filter Warning',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 163,
                'offset' => 5,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 1 - Compressor A Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 163,
                'offset' => 6,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 1 - Compressor B Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 163,
                'offset' => 7,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 1 - Compressor Refrigerant Differential Pressure Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 163,
                'offset' => 8,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 1 - Condenser Fan Overload Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 163,
                'offset' => 9,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 1 - Condenser Fan VFD Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 163,
                'offset' => 10,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 1 - Discharge Pressure Sensor Failure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 163,
                'offset' => 11,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 1 - EVD Failure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 163,
                'offset' => 12,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 1 - High Discharge Pressure Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 163,
                'offset' => 13,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 1 - High Discharge Pressure Switch Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 163,
                'offset' => 14,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 1 - Low Discharge Pressure Warning',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 164,
                'offset' => 0,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 1 - Low Fluid Flow Warning',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 164,
                'offset' => 1,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 1 - Low Suction Pressure Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 164,
                'offset' => 2,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 1 - Low Superheat Temperature Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 164,
                'offset' => 3,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 1 - Retain Memory Write Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 164,
                'offset' => 4,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 1 - Too Many Writes to Retentive Memory Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 164,
                'offset' => 5,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 1 - Phase Monitor Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 164,
                'offset' => 6,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 1 - Reduced Fluid Flow Sensor Failure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 164,
                'offset' => 7,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 1 - Reduced Fluid Flow Warning',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 164,
                'offset' => 9,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 1 - Refrigerant Liquid Temperature Sensor Failure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 164,
                'offset' => 10,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 1 - Suction Pressure Sensor Failure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 164,
                'offset' => 11,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 1 - High Suction Pressure Warning',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 165,
                'offset' => 0,
                'bytes' => 1
            ],



            [
                'name' => 'Circuit 2 - Ambient Temperature Sensor Faiure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 166,
                'offset' => 0,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 2 - Aux Alarm Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 166,
                'offset' => 1,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 2 - Aux Alarm Warning',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 166,
                'offset' => 2,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 2 - Chill In Temperature Sensor Failure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 166,
                'offset' => 3,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 2 - Chill Out Temperature Sensor Failure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 166,
                'offset' => 4,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 2 - Chilled Water Dirty Filter Warning',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 166,
                'offset' => 5,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 2 - Compressor A Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 166,
                'offset' => 6,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 2 - Compressor B Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 166,
                'offset' => 7,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 2 - Compressor Refrigerant Differential Pressure Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 166,
                'offset' => 8,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 2 - Condenser Fan Overload Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 166,
                'offset' => 9,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 2 - Condenser Fan VFD Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 166,
                'offset' => 10,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 2 - Discharge Pressure Sensor Failure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 166,
                'offset' => 11,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 2 - EVD Failure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 166,
                'offset' => 12,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 2 - High Discharge Pressure Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 166,
                'offset' => 13,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 2 - High Discharge Pressure Switch Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 166,
                'offset' => 14,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 2 - Low Discharge Pressure Warning',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 167,
                'offset' => 0,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 2 - Low Fluid Flow Warning',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 167,
                'offset' => 1,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 2 - Low Suction Pressure Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 167,
                'offset' => 2,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 2 - Low Superheat Temperature Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 167,
                'offset' => 3,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 2 - Retain Memory Write Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 167,
                'offset' => 4,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 2 - Too Many Writes to Retentive Memory Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 167,
                'offset' => 5,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 2 - Phase Monitor Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 167,
                'offset' => 6,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 2 - Reduced Fluid Flow Sensor Failure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 167,
                'offset' => 7,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 2 - Reduced Fluid Flow Warning',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 167,
                'offset' => 9,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 2 - Refrigerant Liquid Temperature Sensor Failure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 167,
                'offset' => 10,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 2 - Suction Pressure Sensor Failure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 167,
                'offset' => 11,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 2 - High Suction Pressure Warning',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 168,
                'offset' => 0,
                'bytes' => 1
            ],


            [
                'name' => 'Circuit 3 - Ambient Temperature Sensor Faiure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 169,
                'offset' => 0,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 3 - Aux Alarm Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 169,
                'offset' => 1,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 3 - Aux Alarm Warning',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 169,
                'offset' => 2,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 3 - Chill In Temperature Sensor Failure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 169,
                'offset' => 3,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 3 - Chill Out Temperature Sensor Failure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 169,
                'offset' => 4,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 3 - Chilled Water Dirty Filter Warning',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 169,
                'offset' => 5,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 3 - Compressor A Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 169,
                'offset' => 6,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 3 - Compressor B Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 169,
                'offset' => 7,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 3 - Compressor Refrigerant Differential Pressure Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 169,
                'offset' => 8,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 3 - Condenser Fan Overload Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 169,
                'offset' => 9,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 3 - Condenser Fan VFD Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 169,
                'offset' => 10,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 3 - Discharge Pressure Sensor Failure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 169,
                'offset' => 11,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 3 - EVD Failure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 169,
                'offset' => 12,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 3 - High Discharge Pressure Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 169,
                'offset' => 13,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 3 - High Discharge Pressure Switch Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 169,
                'offset' => 14,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 3 - Low Discharge Pressure Warning',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 170,
                'offset' => 0,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 3 - Low Fluid Flow Warning',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 170,
                'offset' => 1,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 3 - Low Suction Pressure Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 170,
                'offset' => 2,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 3 - Low Superheat Temperature Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 170,
                'offset' => 3,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 3 - Retain Memory Write Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 170,
                'offset' => 4,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 3 - Too Many Writes to Retentive Memory Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 170,
                'offset' => 5,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 3 - Phase Monitor Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 170,
                'offset' => 6,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 3 - Reduced Fluid Flow Sensor Failure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 170,
                'offset' => 7,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 3 - Reduced Fluid Flow Warning',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 170,
                'offset' => 9,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 3 - Refrigerant Liquid Temperature Sensor Failure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 170,
                'offset' => 10,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 3 - Suction Pressure Sensor Failure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 170,
                'offset' => 11,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 3 - High Suction Pressure Warning',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 171,
                'offset' => 0,
                'bytes' => 1
            ],


            [
                'name' => 'Circuit 4 - Ambient Temperature Sensor Faiure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 172,
                'offset' => 0,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 4 - Aux Alarm Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 172,
                'offset' => 1,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 4 - Aux Alarm Warning',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 172,
                'offset' => 2,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 4 - Chill In Temperature Sensor Failure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 172,
                'offset' => 3,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 4 - Chill Out Temperature Sensor Failure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 172,
                'offset' => 4,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 4 - Chilled Water Dirty Filter Warning',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 172,
                'offset' => 5,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 4 - Compressor A Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 172,
                'offset' => 6,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 4 - Compressor B Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 172,
                'offset' => 7,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 4 - Compressor Refrigerant Differential Pressure Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 172,
                'offset' => 8,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 4 - Condenser Fan Overload Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 172,
                'offset' => 9,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 4 - Condenser Fan VFD Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 172,
                'offset' => 10,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 4 - Discharge Pressure Sensor Failure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 172,
                'offset' => 11,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 4 - EVD Failure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 172,
                'offset' => 12,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 4 - High Discharge Pressure Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 172,
                'offset' => 13,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 4 - High Discharge Pressure Switch Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 172,
                'offset' => 14,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 4 - Low Discharge Pressure Warning',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 173,
                'offset' => 0,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 4 - Low Fluid Flow Warning',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 173,
                'offset' => 1,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 4 - Low Suction Pressure Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 173,
                'offset' => 2,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 4 - Low Superheat Temperature Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 173,
                'offset' => 3,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 4 - Retain Memory Write Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 173,
                'offset' => 4,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 4 - Too Many Writes to Retentive Memory Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 173,
                'offset' => 5,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 4 - Phase Monitor Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 173,
                'offset' => 6,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 4 - Reduced Fluid Flow Sensor Failure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 173,
                'offset' => 7,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 4 - Reduced Fluid Flow Warning',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 173,
                'offset' => 9,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 4 - Refrigerant Liquid Temperature Sensor Failure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 173,
                'offset' => 10,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 4 - Suction Pressure Sensor Failure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 173,
                'offset' => 11,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 4 - High Suction Pressure Warning',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 174,
                'offset' => 0,
                'bytes' => 1
            ],


            [
                'name' => 'Circuit 5 - Ambient Temperature Sensor Faiure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 175,
                'offset' => 0,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 5 - Aux Alarm Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 175,
                'offset' => 1,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 5 - Aux Alarm Warning',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 175,
                'offset' => 2,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 5 - Chill In Temperature Sensor Failure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 175,
                'offset' => 3,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 5 - Chill Out Temperature Sensor Failure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 175,
                'offset' => 4,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 5 - Chilled Water Dirty Filter Warning',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 175,
                'offset' => 5,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 5 - Compressor A Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 175,
                'offset' => 6,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 5 - Compressor B Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 175,
                'offset' => 7,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 5 - Compressor Refrigerant Differential Pressure Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 175,
                'offset' => 8,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 5 - Condenser Fan Overload Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 175,
                'offset' => 9,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 5 - Condenser Fan VFD Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 175,
                'offset' => 10,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 5 - Discharge Pressure Sensor Failure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 175,
                'offset' => 11,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 5 - EVD Failure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 175,
                'offset' => 12,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 5 - High Discharge Pressure Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 175,
                'offset' => 13,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 5 - High Discharge Pressure Switch Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 175,
                'offset' => 14,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 5 - Low Discharge Pressure Warning',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 176,
                'offset' => 0,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 5 - Low Fluid Flow Warning',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 176,
                'offset' => 1,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 5 - Low Suction Pressure Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 176,
                'offset' => 2,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 5 - Low Superheat Temperature Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 176,
                'offset' => 3,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 5 - Retain Memory Write Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 176,
                'offset' => 4,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 5 - Too Many Writes to Retentive Memory Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 176,
                'offset' => 5,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 5 - Phase Monitor Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 176,
                'offset' => 6,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 5 - Reduced Fluid Flow Sensor Failure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 176,
                'offset' => 7,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 5 - Reduced Fluid Flow Warning',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 176,
                'offset' => 9,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 5 - Refrigerant Liquid Temperature Sensor Failure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 176,
                'offset' => 10,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 5 - Suction Pressure Sensor Failure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 176,
                'offset' => 11,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 5 - High Suction Pressure Warning',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 177,
                'offset' => 0,
                'bytes' => 1
            ],

            [
                'name' => 'Circuit 6 - Ambient Temperature Sensor Faiure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 178,
                'offset' => 0,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 6 - Aux Alarm Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 178,
                'offset' => 1,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 6 - Aux Alarm Warning',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 178,
                'offset' => 2,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 6 - Chill In Temperature Sensor Failure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 178,
                'offset' => 3,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 6 - Chill Out Temperature Sensor Failure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 178,
                'offset' => 4,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 6 - Chilled Water Dirty Filter Warning',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 178,
                'offset' => 5,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 6 - Compressor A Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 178,
                'offset' => 6,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 6 - Compressor B Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 178,
                'offset' => 7,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 6 - Compressor Refrigerant Differential Pressure Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 178,
                'offset' => 8,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 6 - Condenser Fan Overload Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 178,
                'offset' => 9,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 6 - Condenser Fan VFD Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 178,
                'offset' => 10,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 6 - Discharge Pressure Sensor Failure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 178,
                'offset' => 11,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 6 - EVD Failure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 178,
                'offset' => 12,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 6 - High Discharge Pressure Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 178,
                'offset' => 13,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 6 - High Discharge Pressure Switch Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 178,
                'offset' => 14,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 6 - Low Discharge Pressure Warning',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 179,
                'offset' => 0,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 6 - Low Fluid Flow Warning',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 179,
                'offset' => 1,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 6 - Low Suction Pressure Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 179,
                'offset' => 2,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 6 - Low Superheat Temperature Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 179,
                'offset' => 3,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 6 - Retain Memory Write Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 179,
                'offset' => 4,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 6 - Too Many Writes to Retentive Memory Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 179,
                'offset' => 5,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 6 - Phase Monitor Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 179,
                'offset' => 6,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 6 - Reduced Fluid Flow Sensor Failure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 179,
                'offset' => 7,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 6 - Reduced Fluid Flow Warning',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 179,
                'offset' => 9,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 6 - Refrigerant Liquid Temperature Sensor Failure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 179,
                'offset' => 10,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 6 - Suction Pressure Sensor Failure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 179,
                'offset' => 11,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 6 - High Suction Pressure Warning',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 180,
                'offset' => 0,
                'bytes' => 1
            ],

            [
                'name' => 'Circuit 7 - Ambient Temperature Sensor Faiure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 181,
                'offset' => 0,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 7 - Aux Alarm Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 181,
                'offset' => 1,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 7 - Aux Alarm Warning',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 181,
                'offset' => 2,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 7 - Chill In Temperature Sensor Failure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 181,
                'offset' => 3,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 7 - Chill Out Temperature Sensor Failure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 181,
                'offset' => 4,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 7 - Chilled Water Dirty Filter Warning',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 181,
                'offset' => 5,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 7 - Compressor A Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 181,
                'offset' => 6,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 7 - Compressor B Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 181,
                'offset' => 7,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 7 - Compressor Refrigerant Differential Pressure Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 181,
                'offset' => 8,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 7 - Condenser Fan Overload Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 181,
                'offset' => 9,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 7 - Condenser Fan VFD Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 181,
                'offset' => 10,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 7 - Discharge Pressure Sensor Failure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 181,
                'offset' => 11,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 7 - EVD Failure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 181,
                'offset' => 12,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 7 - High Discharge Pressure Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 181,
                'offset' => 13,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 7 - High Discharge Pressure Switch Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 181,
                'offset' => 14,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 7 - Low Discharge Pressure Warning',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 182,
                'offset' => 0,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 7 - Low Fluid Flow Warning',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 182,
                'offset' => 1,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 7 - Low Suction Pressure Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 182,
                'offset' => 2,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 7 - Low Superheat Temperature Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 182,
                'offset' => 3,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 7 - Retain Memory Write Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 182,
                'offset' => 4,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 7 - Too Many Writes to Retentive Memory Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 182,
                'offset' => 5,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 7 - Phase Monitor Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 182,
                'offset' => 6,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 7 - Reduced Fluid Flow Sensor Failure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 182,
                'offset' => 7,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 7 - Reduced Fluid Flow Warning',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 182,
                'offset' => 9,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 7 - Refrigerant Liquid Temperature Sensor Failure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 182,
                'offset' => 10,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 7 - Suction Pressure Sensor Failure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 182,
                'offset' => 11,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 7 - High Suction Pressure Warning',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 183,
                'offset' => 0,
                'bytes' => 1
            ],


            [
                'name' => 'Circuit 8 - Ambient Temperature Sensor Faiure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 184,
                'offset' => 0,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 8 - Aux Alarm Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 184,
                'offset' => 1,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 8 - Aux Alarm Warning',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 184,
                'offset' => 2,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 8 - Chill In Temperature Sensor Failure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 184,
                'offset' => 3,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 8 - Chill Out Temperature Sensor Failure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 184,
                'offset' => 4,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 8 - Chilled Water Dirty Filter Warning',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 184,
                'offset' => 5,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 8 - Compressor A Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 184,
                'offset' => 6,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 8 - Compressor B Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 184,
                'offset' => 7,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 8 - Compressor Refrigerant Differential Pressure Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 184,
                'offset' => 8,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 8 - Condenser Fan Overload Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 184,
                'offset' => 9,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 8 - Condenser Fan VFD Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 184,
                'offset' => 10,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 8 - Discharge Pressure Sensor Failure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 184,
                'offset' => 11,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 8 - EVD Failure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 184,
                'offset' => 12,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 8 - High Discharge Pressure Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 184,
                'offset' => 13,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 8 - High Discharge Pressure Switch Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 184,
                'offset' => 14,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 8 - Low Discharge Pressure Warning',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 185,
                'offset' => 0,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 8 - Low Fluid Flow Warning',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 185,
                'offset' => 1,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 8 - Low Suction Pressure Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 185,
                'offset' => 2,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 8 - Low Superheat Temperature Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 185,
                'offset' => 3,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 8 - Retain Memory Write Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 185,
                'offset' => 4,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 8 - Too Many Writes to Retentive Memory Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 185,
                'offset' => 5,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 8 - Phase Monitor Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 185,
                'offset' => 6,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 8 - Reduced Fluid Flow Sensor Failure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 185,
                'offset' => 7,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 8 - Reduced Fluid Flow Warning',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 185,
                'offset' => 9,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 8 - Refrigerant Liquid Temperature Sensor Failure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 185,
                'offset' => 10,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 8 - Suction Pressure Sensor Failure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 185,
                'offset' => 11,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 8 - High Suction Pressure Warning',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 186,
                'offset' => 0,
                'bytes' => 1
            ],

            [
                'name' => 'Circuit 9 - Ambient Temperature Sensor Faiure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 187,
                'offset' => 0,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 9 - Aux Alarm Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 187,
                'offset' => 1,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 9 - Aux Alarm Warning',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 187,
                'offset' => 2,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 9 - Chill In Temperature Sensor Failure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 187,
                'offset' => 3,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 9 - Chill Out Temperature Sensor Failure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 187,
                'offset' => 4,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 9 - Chilled Water Dirty Filter Warning',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 187,
                'offset' => 5,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 9 - Compressor A Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 187,
                'offset' => 6,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 9 - Compressor B Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 187,
                'offset' => 7,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 9 - Compressor Refrigerant Differential Pressure Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 187,
                'offset' => 8,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 9 - Condenser Fan Overload Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 187,
                'offset' => 9,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 9 - Condenser Fan VFD Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 187,
                'offset' => 10,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 9 - Discharge Pressure Sensor Failure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 187,
                'offset' => 11,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 9 - EVD Failure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 187,
                'offset' => 12,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 9 - High Discharge Pressure Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 187,
                'offset' => 13,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 9 - High Discharge Pressure Switch Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 187,
                'offset' => 14,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 9 - Low Discharge Pressure Warning',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 188,
                'offset' => 0,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 9 - Low Fluid Flow Warning',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 188,
                'offset' => 1,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 9 - Low Suction Pressure Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 188,
                'offset' => 2,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 9 - Low Superheat Temperature Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 188,
                'offset' => 3,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 9 - Retain Memory Write Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 188,
                'offset' => 4,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 9 - Too Many Writes to Retentive Memory Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 188,
                'offset' => 5,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 9 - Phase Monitor Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 188,
                'offset' => 6,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 9 - Reduced Fluid Flow Sensor Failure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 188,
                'offset' => 7,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 9 - Reduced Fluid Flow Warning',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 188,
                'offset' => 9,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 9 - Refrigerant Liquid Temperature Sensor Failure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 188,
                'offset' => 10,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 9 - Suction Pressure Sensor Failure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 188,
                'offset' => 11,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 9 - High Suction Pressure Warning',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 189,
                'offset' => 0,
                'bytes' => 1
            ],

            [
                'name' => 'Circuit 10 - Ambient Temperature Sensor Faiure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 190,
                'offset' => 0,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 10 - Aux Alarm Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 190,
                'offset' => 1,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 10 - Aux Alarm Warning',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 190,
                'offset' => 2,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 10 - Chill In Temperature Sensor Failure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 190,
                'offset' => 3,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 10 - Chill Out Temperature Sensor Failure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 190,
                'offset' => 4,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 10 - Chilled Water Dirty Filter Warning',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 190,
                'offset' => 5,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 10 - Compressor A Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 190,
                'offset' => 6,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 10 - Compressor B Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 190,
                'offset' => 7,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 10 - Compressor Refrigerant Differential Pressure Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 190,
                'offset' => 8,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 10 - Condenser Fan Overload Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 190,
                'offset' => 9,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 10 - Condenser Fan VFD Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 190,
                'offset' => 10,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 10 - Discharge Pressure Sensor Failure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 190,
                'offset' => 11,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 10 - EVD Failure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 190,
                'offset' => 12,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 10 - High Discharge Pressure Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 190,
                'offset' => 13,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 10 - High Discharge Pressure Switch Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 190,
                'offset' => 14,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 10 - Low Discharge Pressure Warning',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 191,
                'offset' => 0,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 10 - Low Fluid Flow Warning',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 191,
                'offset' => 1,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 10 - Low Suction Pressure Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 191,
                'offset' => 2,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 10 - Low Superheat Temperature Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 191,
                'offset' => 3,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 10 - Retain Memory Write Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 191,
                'offset' => 4,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 10 - Too Many Writes to Retentive Memory Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 191,
                'offset' => 5,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 10 - Phase Monitor Fault',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 191,
                'offset' => 6,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 10 - Reduced Fluid Flow Sensor Failure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 191,
                'offset' => 7,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 10 - Reduced Fluid Flow Warning',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 191,
                'offset' => 9,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 10 - Refrigerant Liquid Temperature Sensor Failure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 191,
                'offset' => 10,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 10 - Suction Pressure Sensor Failure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 191,
                'offset' => 11,
                'bytes' => 1
            ],
            [
                'name' => 'Circuit 10 - High Suction Pressure Warning',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 192,
                'offset' => 0,
                'bytes' => 1
            ],


            [
                'name' => 'Communications Loss Circuit 2',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 162,
                'offset' => 0,
                'bytes' => 1
            ], [
                'name' => 'Communications Loss Circuit 3',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 162,
                'offset' => 1,
                'bytes' => 1
            ], [
                'name' => 'Communications Loss Circuit 4',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 162,
                'offset' => 2,
                'bytes' => 1
            ], [
                'name' => 'Communications Loss Circuit 5',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 162,
                'offset' => 3,
                'bytes' => 1
            ], [
                'name' => 'Communications Loss Circuit 6',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 162,
                'offset' => 4,
                'bytes' => 1
            ], [
                'name' => 'Communications Loss Circuit 7',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 162,
                'offset' => 5,
                'bytes' => 1
            ], [
                'name' => 'Communications Loss Circuit 8',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 162,
                'offset' => 6,
                'bytes' => 1
            ], [
                'name' => 'Communications Loss Circuit 9',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 162,
                'offset' => 7,
                'bytes' => 1
            ], [
                'name' => 'Communications Loss Circuit 10',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 162,
                'offset' => 8,
                'bytes' => 1
            ], [
                'name' => 'Low Fluid Temperature Fault ',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 162,
                'offset' => 9,
                'bytes' => 1
            ], [
                'name' => 'High Fluid Temperature Warning',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 162,
                'offset' => 10,
                'bytes' => 1
            ], [
                'name' => 'Remote Setpoint Signal Failure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 162,
                'offset' => 11,
                'bytes' => 1
            ], [
                'name' => 'Tank Temperature Sensor Failure',
                'machine_id' => MACHINE_HE_CENTRAL_CHILLER,
                'tag_id' => 162,
                'offset' => 12,
                'bytes' => 1
            ]
       ];

        DB::table('alarm_types')->insert($alarm_types);
    }
}