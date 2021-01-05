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
                'tag_id' => 27
            ], [
	          	'name' => 'Out of Material',
                'machine_id' => MACHINE_BD_BATCH_BLENDER,
                'tag_id' => 28
	      	], [
	          	'name' => 'Hopper Unstable (Feeder 1)',
                'machine_id' => MACHINE_BD_BATCH_BLENDER,
                'tag_id' => 31
	      	], [
                'name' => 'Hopper Unstable (Feeder 2)',
                'machine_id' => MACHINE_BD_BATCH_BLENDER,
                'tag_id' => 32
            ], [
                'name' => 'Hopper Unstable (Feeder 3)',
                'machine_id' => MACHINE_BD_BATCH_BLENDER,
                'tag_id' => 33
            ], [
                'name' => 'Hopper Unstable (Feeder 4)',
                'machine_id' => MACHINE_BD_BATCH_BLENDER,
                'tag_id' => 34
            ], [
                'name' => 'Hopper Unstable (Feeder 5)',
                'machine_id' => MACHINE_BD_BATCH_BLENDER,
                'tag_id' => 35
            ], [
                'name' => 'Hopper Unstable (Feeder 6)',
                'machine_id' => MACHINE_BD_BATCH_BLENDER,
                'tag_id' => 36
            ], [
                'name' => 'Hopper Unstable (Feeder 7)',
                'machine_id' => MACHINE_BD_BATCH_BLENDER,
                'tag_id' => 37
            ], [
                'name' => 'Hopper Unstable (Feeder 8)',
                'machine_id' => MACHINE_BD_BATCH_BLENDER,
                'tag_id' => 38
            ], [
                'name' => 'Hopper Overfeed',
                'machine_id' => MACHINE_BD_BATCH_BLENDER,
                'tag_id' => 39
            ], [
                'name' => 'Hopper Over Max',
                'machine_id' => MACHINE_BD_BATCH_BLENDER,
                'tag_id' => 40
            ], [
				'name' => 'Unable to make rate',
                'machine_id' => MACHINE_BD_BATCH_BLENDER,
                'tag_id' => 43
	      	], [
                'name' => 'Max Empty Weight',
                'machine_id' => MACHINE_BD_BATCH_BLENDER,
                'tag_id' => 41
            ], [
                'name' => 'Pumper Starter Fault',
                'machine_id' => MACHINE_BD_BATCH_BLENDER,
                'tag_id' => 44
            ], [
                'name' => 'Mixer Failure',
                'machine_id' => MACHINE_BD_BATCH_BLENDER,
                'tag_id' => 42
            ], [
                'name' => 'System Not Stable',
                'machine_id' => 3,
                'tag_id' => 30
            ], [
                'name' => 'Out Of Material',
                'machine_id' => 3,
                'tag_id' => 30
            ], [
                'name' => 'Load Cell Failure',
                'machine_id' => 3,
                'tag_id' => 30
            ], [
                'name' => 'No Extruder Flow',
                'machine_id' => 3,
                'tag_id' => 30
            ], [
                'name' => 'Extruder Drive Failure',
                'machine_id' => 3,
                'tag_id' => 30
            ], [
                'name' => 'Hauloff Drive Failure',
                'machine_id' => 3,
                'tag_id' => 30
            ], [
                'name' => 'Extruder Underspeed',
                'machine_id' => 3,
                'tag_id' => 30
            ], [
                'name' => 'Extruder Overspeed',
                'machine_id' => 3,
                'tag_id' => 30
            ], [
                'name' => 'Hauloff Underspeed',
                'machine_id' => 3,
                'tag_id' => 30
            ], [
                'name' => 'Hauloff Overspeed',
                'machine_id' => 3,
                'tag_id' => 30
            ], [
                'name' => 'Hopper Over Max Flow',
                'machine_id' => 3,
                'tag_id' => 30
            ], [
                'name' => 'Out Of Material',
                'machine_id' => 2,
                'tag_id' => 28
            ], [
                'name' => 'Out Of Material',
                'machine_id' => 2,
                'tag_id' => 29
            ], [
                'name' => 'Out Of Material',
                'machine_id' => 2,
                'tag_id' => 30
            ], [
                'name' => 'Out Of Material',
                'machine_id' => 2,
                'tag_id' => 31
            ], [
                'name' => 'Out Of Material',
                'machine_id' => 2,
                'tag_id' => 32
            ], [
                'name' => 'Out Of Material',
                'machine_id' => 2,
                'tag_id' => 33
            ], [
                'name' => 'Load Cell Failure',
                'machine_id' => 2,
                'tag_id' => 34
            ], [
                'name' => 'Load Cell Failure',
                'machine_id' => 2,
                'tag_id' => 35
            ], [
                'name' => 'Load Cell Failure',
                'machine_id' => 2,
                'tag_id' => 36
            ], [
                'name' => 'Load Cell Failure',
                'machine_id' => 2,
                'tag_id' => 37
            ], [
                'name' => 'Load Cell Failure',
                'machine_id' => 2,
                'tag_id' => 38
            ], [
                'name' => 'Load Cell Failure',
                'machine_id' => 2,
                'tag_id' => 39
            ], [
                'name' => 'Load Cell Overload',
                'machine_id' => 2,
                'tag_id' => 34
            ], [
                'name' => 'Load Cell Overload',
                'machine_id' => 2,
                'tag_id' => 35
            ], [
                'name' => 'Load Cell Overload',
                'machine_id' => 2,
                'tag_id' => 36
            ], [
                'name' => 'Load Cell Overload',
                'machine_id' => 2,
                'tag_id' => 37
            ], [
                'name' => 'Load Cell Overload',
                'machine_id' => 2,
                'tag_id' => 38
            ], [
                'name' => 'Load Cell Overload',
                'machine_id' => 2,
                'tag_id' => 39
            ], [
                'name' => 'Load Cell Overload',
                'machine_id' => 2,
                'tag_id' => 41
            ], [
                'name' => 'Load Cell Overload',
                'machine_id' => 2,
                'tag_id' => 42
            ], [
                'name' => 'Load Cell Overload',
                'machine_id' => 2,
                'tag_id' => 43
            ], [
                'name' => 'Load Cell Overload',
                'machine_id' => 2,
                'tag_id' => 44
            ], [
                'name' => 'Load Cell Overload',
                'machine_id' => 2,
                'tag_id' => 45
            ], [
                'name' => 'Load Cell Overload',
                'machine_id' => 2,
                'tag_id' => 46
            ], [
                'name' => 'No Flow',
                'machine_id' => 2,
                'tag_id' => 47
            ], [
                'name' => 'No Flow',
                'machine_id' => 2,
                'tag_id' => 48
            ], [
                'name' => 'No Flow',
                'machine_id' => 2,
                'tag_id' => 49
            ], [
                'name' => 'No Flow',
                'machine_id' => 2,
                'tag_id' => 50
            ], [
                'name' => 'No Flow',
                'machine_id' => 2,
                'tag_id' => 51
            ], [
                'name' => 'No Flow',
                'machine_id' => 2,
                'tag_id' => 52
            ], [
                'name' => 'Unable to make rate',
                'machine_id' => 2,
                'tag_id' => 53
            ], [
                'name' => 'Bad Blend',
                'machine_id' => 2,
                'tag_id' => 54
            ], [
                'name' => 'Blender Rate Out Of Range',
                'machine_id' => 2,
                'tag_id' => 55
            ], [
                'name' => 'System Not Stable',
                'machine_id' => 4,
                'tag_id' => 30
            ], [
                'name' => 'Out Of Material',
                'machine_id' => 4,
                'tag_id' => 30
            ], [
                'name' => 'Load Cell Failure',
                'machine_id' => 4,
                'tag_id' => 30
            ], [
                'name' => 'No Feeder Flow',
                'machine_id' => 4,
                'tag_id' => 30
            ], [
                'name' => 'Feeder Under Speed',
                'machine_id' => 4,
                'tag_id' => 30
            ], [
                'name' => 'Pump Oil Change Required',
                'machine_id' => 5,
                'tag_id' => 18
            ], [
                'name' => 'Power Loss While Running',
                'machine_id' => 5,
                'tag_id' => 19
            ], [
                'name' => 'Pump Starter Fault',
                'machine_id' => 5,
                'tag_id' => 20
            ], [
                'name' => 'Vacuum Levels Option',
                'machine_id' => 5,
                'tag_id' => 21
            ], [
                'name' => 'Dirty Filter Pressure Switch Warning',
                'machine_id' => 6,
                'tag_id' => 60
            ], [
                'name' => 'Dew Point Sensor Failure Fault',
                'machine_id' => 6,
                'tag_id' => 60
            ], [
                'name' => 'High Dew Point Warning',
                'machine_id' => 6,
                'tag_id' => 60
            ], [
                'name' => 'Process Blower Rotation Failure Fault',
                'machine_id' => 6,
                'tag_id' => 60
            ], [
                'name' => 'DH1 Process Low Temperature Warning',
                'machine_id' => 6,
                'tag_id' => 60
            ], [
                'name' => 'DH1 Process High Temperature Fault',
                'machine_id' => 6,
                'tag_id' => 60
            ], [
                'name' => 'DH1 Process Temp Loop Break Fault',
                'machine_id' => 6,
                'tag_id' => 60
            ], [
                'name' => 'DH1 Process Return Temp Sensor Failure Fault',
                'machine_id' => 6,
                'tag_id' => 60
            ], [
                'name' => 'DH1 Process Supply Temp Sensor Failure Fault',
                'machine_id' => 6,
                'tag_id' => 60
            ], [
                'name' => 'DH1 Process Heater Safety Fault',
                'machine_id' => 6,
                'tag_id' => 60
            ], [
                'name' => 'Regen Exhaust Temp Sensor Failure Fault',
                'machine_id' => 6,
                'tag_id' => 60
            ], [
                'name' => 'Regen Left Bed Heater Temp Sensor Failure Fault',
                'machine_id' => 6,
                'tag_id' => 60
            ], [
                'name' => 'Regen Low air Temperature Fault',
                'machine_id' => 6,
                'tag_id' => 60
            ], [
                'name' => 'Regen Right Bed Heater Temp Sensor Failure Fault',
                'machine_id' => 6,
                'tag_id' => 60
            ], [
                'name' => 'Regen Heater Safety Fault',
                'machine_id' => 6,
                'tag_id' => 60
            ], [
                'name' => 'Process Air Valve Position Fault',
                'machine_id' => 6,
                'tag_id' => 60
            ], [
                'name' => 'Process Blower Overload Fault',
                'machine_id' => 6,
                'tag_id' => 60
            ], [
                'name' => 'DH2 Process High Temperature Fault',
                'machine_id' => 6,
                'tag_id' => 60
            ], [
                'name' => 'DH3 Process High Temperature Fault',
                'machine_id' => 6,
                'tag_id' => 60
            ], [
                'name' => 'DH2 Process Supply Temp Sensor Failure Fault',
                'machine_id' => 6,
                'tag_id' => 60
            ], [
                'name' => 'DH3 Process Supply Temp Sensor Failure Fault',
                'machine_id' => 6,
                'tag_id' => 60
            ], [
                'name' => 'DH2 Process Heater Safety Fault',
                'machine_id' => 6,
                'tag_id' => 60
            ], [
                'name' => 'DH3 Process Heater Safety Fault',
                'machine_id' => 6,
                'tag_id' => 60
            ], [
                'name' => 'DH2 Process Temp Loop Break Fault',
                'machine_id' => 6,
                'tag_id' => 60
            ], [
                'name' => 'DH3 Process Temp Loop Break Fault',
                'machine_id' => 6,
                'tag_id' => 60
            ], [
                'name' => 'DH2 Process Return Temp Sensor Failure Fault',
                'machine_id' => 6,
                'tag_id' => 60
            ], [
                'name' => 'DH3 Process Return Temp Sensor Failure Fault',
                'machine_id' => 6,
                'tag_id' => 60
            ], [
                'name' => 'Regen Heater High Temp Fault',
                'machine_id' => 6,
                'tag_id' => 60
            ], [
                'name' => 'Bed Break Temperature Not Reached Warning',
                'machine_id' => 6,
                'tag_id' => 60
            ], [
                'name' => 'Regen Exhaust High - Cooling Extended Warning',
                'machine_id' => 6,
                'tag_id' => 60
            ], [
                'name' => 'Regen Cooling Taking Too Long Fault ',
                'machine_id' => 6,
                'tag_id' => 60
            ], [
                'name' => 'High Dew Point Alarm Fault',
                'machine_id' => 6,
                'tag_id' => 61
            ], [
                'name' => 'Regen Air Valve Position Fault',
                'machine_id' => 6,
                'tag_id' => 61
            ], [
                'name' => 'E-Stop Fault',
                'machine_id' => 6,
                'tag_id' => 61
            ], [
                'name' => 'PLC Expansion Card Error - Slot 1 Fault',
                'machine_id' => 6,
                'tag_id' => 61
            ], [
                'name' => 'PLC Expansion Card Error - Slot 2 Fault',
                'machine_id' => 6,
                'tag_id' => 61
            ], [
                'name' => 'Return Air Over Temperature Fault',
                'machine_id' => 6,
                'tag_id' => 61
            ], [
                'name' => 'DH2 Process Low Temperature Warning',
                'machine_id' => 6,
                'tag_id' => 61
            ], [
                'name' => 'DH3 Process Low Temperature Warning',
                'machine_id' => 6,
                'tag_id' => 61
            ], [
                'name' => 'PLC Expansion Card Error - Slot 3 Fault',
                'machine_id' => 6,
                'tag_id' => 61
            ], [
                'name' => 'Mach Loader MCP tripped',
                'machine_id' => 6,
                'tag_id' => 61
            ], [
                'name' => 'Mach Loader Aux run contact',
                'machine_id' => 6,
                'tag_id' => 61
            ], [
                'name' => 'Mach Loader 1 no convey',
                'machine_id' => 6,
                'tag_id' => 61
            ], [
                'name' => 'Mach Loader 2 no convey',
                'machine_id' => 6,
                'tag_id' => 61
            ], [
                'name' => 'Hopper Loader MCP tripped',
                'machine_id' => 6,
                'tag_id' => 61
            ], [
                'name' => 'Hopper Loader Aux run contact',
                'machine_id' => 6,
                'tag_id' => 61
            ], [
                'name' => 'Hopper Loader no convey',
                'machine_id' => 6,
                'tag_id' => 61
            ], [
                'name' => 'Regen Test Heater Fail',
                'machine_id' => 6,
                'tag_id' => 61
            ], [
                'name' => 'Regen Test Ended - Fail',
                'machine_id' => 6,
                'tag_id' => 61
            ], [
                'name' => 'Regen Test Ended - Pass',
                'machine_id' => 6,
                'tag_id' => 61
            ], [
                'name' => 'After-Cooler T/C failure',
                'machine_id' => 6,
                'tag_id' => 61
            ], [
                'name' => 'After-Cooler high temperature warning',
                'machine_id' => 6,
                'tag_id' => 61
            ], [
                'name' => 'After-Cooler high temperature shutdown',
                'machine_id' => 6,
                'tag_id' => 61
            ], [
                'name' => 'DH1 Return Air high temp Warning',
                'machine_id' => 6,
                'tag_id' => 61
            ], [
                'name' => 'DH1 Return Air high temp shutdown',
                'machine_id' => 6,
                'tag_id' => 61
            ], [
                'name' => 'DH2 Return Air high temp Warning',
                'machine_id' => 6,
                'tag_id' => 61
            ], [
                'name' => 'DH2 Return Air high temp shutdown',
                'machine_id' => 6,
                'tag_id' => 61
            ], [
                'name' => 'DH3 Return Air high temp Warning',
                'machine_id' => 6,
                'tag_id' => 61
            ], [
                'name' => 'DH3 Return Air high temp shutdown',
                'machine_id' => 6,
                'tag_id' => 61
            ], [
                'name' => 'Low Temperature Selected with no Pre-Cooler',
                'machine_id' => 6,
                'tag_id' => 61
            ], [
                'name' => 'High Temperature Selected with no After-Cooler',
                'machine_id' => 6,
                'tag_id' => 61
            ], [
                'name' => 'Dew Point Sensor Failure Warning',
                'machine_id' => 6,
                'tag_id' => 62
            ], [
                'name' => 'Regen Exhaust Sensor Failure Warning',
                'machine_id' => 6,
                'tag_id' => 62
            ], [
                'name' => 'PLC Clock Error Warning',
                'machine_id' => 6,
                'tag_id' => 62
            ], [
                'name' => 'Power On Start',
                'machine_id' => 6,
                'tag_id' => 62
            ], [
                'name' => 'Auto Start',
                'machine_id' => 6,
                'tag_id' => 62
            ], [
                'name' => 'Auto Stop',
                'machine_id' => 6,
                'tag_id' => 62
            ], [
                'name' => 'PLC initialize',
                'machine_id' => 6,
                'tag_id' => 62
            ], [
                'name' => 'DH1 - Pressure switch verification fail',
                'machine_id' => 6,
                'tag_id' => 62
            ], [
                'name' => 'DH2 - Pressure switch verification fail',
                'machine_id' => 6,
                'tag_id' => 62
            ], [
                'name' => 'DH3 - Pressure switch verification fail',
                'machine_id' => 6,
                'tag_id' => 62
            ], [
                'name' => 'Regen Test Fail',
                'machine_id' => 6,
                'tag_id' => 62
            ], [
                'name' => 'Blower Hours Maintenance',
                'machine_id' => 6,
                'tag_id' => 62
            ], [
                'name' => 'Drying Hopper 1 Heater Hours Maintenance',
                'machine_id' => 6,
                'tag_id' => 62
            ], [
                'name' => 'Drying Hopper 2 Heater Hours Maintenance',
                'machine_id' => 6,
                'tag_id' => 62
            ], [
                'name' => 'Drying Hopper 3 Heater Hours Maintenance',
                'machine_id' => 6,
                'tag_id' => 62
            ], [
                'name' => 'Left Regen Heater Hours Maintenance',
                'machine_id' => 6,
                'tag_id' => 62
            ], [
                'name' => 'Right Regen Heater Hours Maintenance',
                'machine_id' => 6,
                'tag_id' => 62
            ], [
                'name' => 'Filter Hours Maintenance',
                'machine_id' => 6,
                'tag_id' => 62
            ], [
                'name' => 'Dryer Online Hours Maintenance',
                'machine_id' => 6,
                'tag_id' => 62
            ], [
                'name' => 'Hopper Loader Blower Hours Maintenance',
                'machine_id' => 6,
                'tag_id' => 62
            ], [
                'name' => 'Machine Loader Blower Hours Maintenance',
                'machine_id' => 6,
                'tag_id' => 62
            ], [
                'name' => 'Dirty Filter Pressure Switch Warning',
                'machine_id' => 7,
                'tag_id' => 46
            ], [
                'name' => 'Dew Point Sensor Failure Fault',
                'machine_id' => 7,
                'tag_id' => 46
            ], [
                'name' => 'High Dew Point Warning',
                'machine_id' => 7,
                'tag_id' => 46
            ], [
                'name' => 'Process Blower Rotation Failure Fault',
                'machine_id' => 7,
                'tag_id' => 46
            ], [
                'name' => 'DH1 Process Low Temperature Warning',
                'machine_id' => 7,
                'tag_id' => 46
            ], [
                'name' => 'DH1 Process High Temperature Fault',
                'machine_id' => 7,
                'tag_id' => 46
            ], [
                'name' => 'DH1 Process Temp Loop Break Fault',
                'machine_id' => 7,
                'tag_id' => 46
            ], [
                'name' => 'DH1 Process Return Temp Sensor Failure Fault',
                'machine_id' => 7,
                'tag_id' => 46
            ], [
                'name' => 'DH1 Process Supply Temp Sensor Failure Fault',
                'machine_id' => 7,
                'tag_id' => 46
            ], [
                'name' => 'DH1 Process Heater Safety Fault',
                'machine_id' => 7,
                'tag_id' => 46
            ], [
                'name' => 'Regen Exhaust Temp Sensor Failure Fault',
                'machine_id' => 7,
                'tag_id' => 46
            ], [
                'name' => 'Regen Left Bed Heater Temp Sensor Failure Fault',
                'machine_id' => 7,
                'tag_id' => 46
            ], [
                'name' => 'Regen Low air Temperature Fault',
                'machine_id' => 7,
                'tag_id' => 46
            ], [
                'name' => 'Regen Right Bed Heater Temp Sensor Failure Fault',
                'machine_id' => 7,
                'tag_id' => 46
            ], [
                'name' => 'Regen Heater Safety Fault',
                'machine_id' => 7,
                'tag_id' => 46
            ], [
                'name' => 'Process Air Valve Position Fault',
                'machine_id' => 7,
                'tag_id' => 46
            ], [
                'name' => 'Process Blower Overload Fault',
                'machine_id' => 7,
                'tag_id' => 46
            ], [
                'name' => 'DH2 Process High Temperature Fault',
                'machine_id' => 7,
                'tag_id' => 46
            ], [
                'name' => 'DH3 Process High Temperature Fault',
                'machine_id' => 7,
                'tag_id' => 46
            ], [
                'name' => 'DH2 Process Supply Temp Sensor Failure Fault',
                'machine_id' => 7,
                'tag_id' => 46
            ], [
                'name' => 'DH3 Process Supply Temp Sensor Failure Fault',
                'machine_id' => 7,
                'tag_id' => 46
            ], [
                'name' => 'DH2 Process Heater Safety Fault',
                'machine_id' => 7,
                'tag_id' => 46
            ], [
                'name' => 'DH3 Process Heater Safety Fault',
                'machine_id' => 7,
                'tag_id' => 46
            ], [
                'name' => 'DH2 Process Temp Loop Break Fault',
                'machine_id' => 7,
                'tag_id' => 46
            ], [
                'name' => 'DH3 Process Temp Loop Break Fault',
                'machine_id' => 7,
                'tag_id' => 46
            ], [
                'name' => 'DH2 Process Return Temp Sensor Failure Fault',
                'machine_id' => 7,
                'tag_id' => 46
            ], [
                'name' => 'DH3 Process Return Temp Sensor Failure Fault',
                'machine_id' => 7,
                'tag_id' => 46
            ], [
                'name' => 'Regen Heater High Temp Fault',
                'machine_id' => 7,
                'tag_id' => 46
            ], [
                'name' => 'Bed Break Temperature Not Reached Warning',
                'machine_id' => 7,
                'tag_id' => 46
            ], [
                'name' => 'Regen Exhaust High - Cooling Extended Warning',
                'machine_id' => 7,
                'tag_id' => 46
            ], [
                'name' => 'Regen Cooling Taking Too Long Fault',
                'machine_id' => 7,
                'tag_id' => 46
            ], [
                'name' => 'High Dew Point Alarm Fault',
                'machine_id' => 7,
                'tag_id' => 47
            ], [
                'name' => 'Regen Air Valve Position Fault',
                'machine_id' => 7,
                'tag_id' => 47
            ], [
                'name' => 'E-Stop Fault',
                'machine_id' => 7,
                'tag_id' => 47
            ], [
                'name' => 'PLC Expansion Card Error - Slot 1 Fault',
                'machine_id' => 7,
                'tag_id' => 47
            ], [
                'name' => 'PLC Expansion Card Error - Slot 2 Fault',
                'machine_id' => 7,
                'tag_id' => 47
            ], [
                'name' => 'Return Air Over Temperature Fault',
                'machine_id' => 7,
                'tag_id' => 47
            ], [
                'name' => 'DH2 Process Low Temperature Warning',
                'machine_id' => 7,
                'tag_id' => 47
            ], [
                'name' => 'DH3 Process Low Temperature Warning',
                'machine_id' => 7,
                'tag_id' => 47
            ], [
                'name' => 'PLC Expansion Card Error - Slot 3 Fault',
                'machine_id' => 7,
                'tag_id' => 47
            ], [
                'name' => 'Mach Loader MCP tripped',
                'machine_id' => 7,
                'tag_id' => 47
            ], [
                'name' => 'Mach Loader Aux run contact',
                'machine_id' => 7,
                'tag_id' => 47
            ], [
                'name' => 'Mach Loader 1 no convey',
                'machine_id' => 7,
                'tag_id' => 47
            ], [
                'name' => 'Mach Loader 2 no convey',
                'machine_id' => 7,
                'tag_id' => 47
            ], [
                'name' => 'Hopper Loader MCP tripped',
                'machine_id' => 7,
                'tag_id' => 47
            ], [
                'name' => 'Hopper Loader Aux run contact',
                'machine_id' => 7,
                'tag_id' => 47
            ], [
                'name' => 'Hopper Loader no convey',
                'machine_id' => 7,
                'tag_id' => 47
            ], [
                'name' => 'Regen Test Heater Fail',
                'machine_id' => 7,
                'tag_id' => 47
            ], [
                'name' => 'Regen Test Ended - Fail',
                'machine_id' => 7,
                'tag_id' => 47
            ], [
                'name' => 'Regen Test Ended - Pass',
                'machine_id' => 7,
                'tag_id' => 47
            ], [
                'name' => 'After-Cooler T/C failure',
                'machine_id' => 7,
                'tag_id' => 47
            ], [
                'name' => 'After-Cooler high temperature warning',
                'machine_id' => 7,
                'tag_id' => 47
            ], [
                'name' => 'After-Cooler high temperature shutdown',
                'machine_id' => 7,
                'tag_id' => 47
            ], [
                'name' => 'DH1 Return Air high temp Warning',
                'machine_id' => 7,
                'tag_id' => 47
            ], [
                'name' => 'DH1 Return Air high temp shutdown',
                'machine_id' => 7,
                'tag_id' => 47
            ], [
                'name' => 'DH2 Return Air high temp Warning',
                'machine_id' => 7,
                'tag_id' => 47
            ], [
                'name' => 'DH2 Return Air high temp shutdown',
                'machine_id' => 7,
                'tag_id' => 47
            ], [
                'name' => 'DH3 Return Air high temp Warning',
                'machine_id' => 7,
                'tag_id' => 47
            ], [
                'name' => 'DH3 Return Air high temp shutdown',
                'machine_id' => 7,
                'tag_id' => 47
            ], [
                'name' => 'Low Temperature Selected with no Pre-Cooler',
                'machine_id' => 7,
                'tag_id' => 47
            ], [
                'name' => 'High Temperature Selected with no After-Cooler',
                'machine_id' => 7,
                'tag_id' => 47
            ], [
                'name' => 'Dew Point Sensor Failure Warning',
                'machine_id' => 7,
                'tag_id' => 48
            ], [
                'name' => 'Regen Exhaust Sensor Failure Warning',
                'machine_id' => 7,
                'tag_id' => 48
            ], [
                'name' => 'PLC Clock Error Warning',
                'machine_id' => 7,
                'tag_id' => 48
            ], [
                'name' => 'Power On Start',
                'machine_id' => 7,
                'tag_id' => 48
            ], [
                'name' => 'Auto Start',
                'machine_id' => 7,
                'tag_id' => 48
            ], [
                'name' => 'Auto Stop',
                'machine_id' => 7,
                'tag_id' => 48
            ], [
                'name' => 'PLC initialize',
                'machine_id' => 7,
                'tag_id' => 48
            ], [
                'name' => 'DH1 - Pressure switch verification fail',
                'machine_id' => 7,
                'tag_id' => 48
            ], [
                'name' => 'DH2 - Pressure switch verification fail',
                'machine_id' => 7,
                'tag_id' => 48
            ], [
                'name' => 'DH3 - Pressure switch verification fail',
                'machine_id' => 7,
                'tag_id' => 48
            ], [
                'name' => 'Regen Test Fail',
                'machine_id' => 7,
                'tag_id' => 48
            ], [
                'name' => 'Blower Hours Maintenance',
                'machine_id' => 7,
                'tag_id' => 48
            ], [
                'name' => 'Drying Hopper 1 Heater Hours Maintenance',
                'machine_id' => 7,
                'tag_id' => 48
            ], [
                'name' => 'Drying Hopper 2 Heater Hours Maintenance',
                'machine_id' => 7,
                'tag_id' => 48
            ], [
                'name' => 'Drying Hopper 3 Heater Hours Maintenance',
                'machine_id' => 7,
                'tag_id' => 48
            ], [
                'name' => 'Left Regen Heater Hours Maintenance',
                'machine_id' => 7,
                'tag_id' => 48
            ], [
                'name' => 'Right Regen Heater Hours Maintenance',
                'machine_id' => 7,
                'tag_id' => 48
            ], [
                'name' => 'Filter Hours Maintenance',
                'machine_id' => 7,
                'tag_id' => 48
            ], [
                'name' => 'Dryer Online Hours Maintenance',
                'machine_id' => 7,
                'tag_id' => 48
            ], [
                'name' => 'Hopper Loader Blower Hours Maintenance',
                'machine_id' => 7,
                'tag_id' => 48
            ], [
                'name' => 'Machine Loader Blower Hours Maintenance',
                'machine_id' => 7,
                'tag_id' => 48
            ], [
                'name' => 'E-Stop Pressed',
                'machine_id' => 8,
                'tag_id' => 47
            ], [
                'name' => '[Aux 1] Fault',
                'machine_id' => 8,
                'tag_id' => 47
            ], [
                'name' => '[Aux 1] Overload Fault',
                'machine_id' => 8,
                'tag_id' => 47
            ], [
                'name' => '[Conveyor] Fault',
                'machine_id' => 8,
                'tag_id' => 47
            ], [
                'name' => '[Conveyor] Overload Fault',
                'machine_id' => 8,
                'tag_id' => 47
            ], [
                'name' => 'Granulator Fault',
                'machine_id' => 8,
                'tag_id' => 47
            ], [
                'name' => 'Granulator Overload Fault',
                'machine_id' => 8,
                'tag_id' => 47
            ], [
                'name' => '[Blower] Fault',
                'machine_id' => 8,
                'tag_id' => 47
            ], [
                'name' => '[Blower] Overload Fault',
                'machine_id' => 8,
                'tag_id' => 47
            ], [
                'name' => '[Aux 2] Fault',
                'machine_id' => 8,
                'tag_id' => 47
            ], [
                'name' => '[Aux 2] Overload Fault',
                'machine_id' => 8,
                'tag_id' => 47
            ], [
                'name' => 'Bearing 1 Temp Sensor Warning',
                'machine_id' => 8,
                'tag_id' => 47
            ], [
                'name' => 'Bearing 2 Temp Sensor Warning',
                'machine_id' => 8,
                'tag_id' => 47
            ], [
                'name' => 'Granulator Amps Sensor Warning',
                'machine_id' => 8,
                'tag_id' => 47
            ], [
                'name' => 'Bearing 1 High Temp Warning',
                'machine_id' => 8,
                'tag_id' => 47
            ], [
                'name' => 'Bearing 2 High Temp Warning',
                'machine_id' => 8,
                'tag_id' => 47
            ], [
                'name' => 'Discharge Bin Not In Place',
                'machine_id' => 8,
                'tag_id' => 47
            ], [
                'name' => 'Rear Access Door Open',
                'machine_id' => 8,
                'tag_id' => 47
            ], [
                'name' => 'Front Access Door Open',
                'machine_id' => 8,
                'tag_id' => 47
            ], [
                'name' => 'Hopper Access Door Open',
                'machine_id' => 8,
                'tag_id' => 47
            ], [
                'name' => 'Rotor Lock Not Retracted',
                'machine_id' => 8,
                'tag_id' => 47
            ], [
                'name' => 'Main Power Not On',
                'machine_id' => 8,
                'tag_id' => 47
            ], [
                'name' => 'Safety Relay output off',
                'machine_id' => 8,
                'tag_id' => 47
            ], [
                'name' => 'Granulator High Current',
                'machine_id' => 8,
                'tag_id' => 47
            ], [
                'name' => 'Bin Full Alarm',
                'machine_id' => 8,
                'tag_id' => 47
            ], [
                'name' => 'Idle - System Shutdown',
                'machine_id' => 8,
                'tag_id' => 48
            ], [
                'name' => 'Bin Full Convey Inhibit',
                'machine_id' => 8,
                'tag_id' => 48
            ], [
                'name' => 'Automatic Run Maintenance Reminder',
                'machine_id' => 8,
                'tag_id' => 48
            ], [
                'name' => 'Granulator Motor Maintenance Reminder',
                'machine_id' => 8,
                'tag_id' => 48
            ], [
                'name' => '[Blower] Motor Maintenance Reminder',
                'machine_id' => 8,
                'tag_id' => 48
            ], [
                'name' => '[Conveyor] Motor Maintenance Reminder',
                'machine_id' => 8,
                'tag_id' => 48
            ], [
                'name' => '[Aux 1] Motor Maintenance Reminder',
                'machine_id' => 8,
                'tag_id' => 48
            ], [
                'name' => '[Aux 2] Motor Maintenance Reminder',
                'machine_id' => 8,
                'tag_id' => 48
            ], [
                'name' => 'PLC Initialized',
                'machine_id' => 8,
                'tag_id' => 48
            ]
        ];
        
        DB::table('alarm_types')->insert($alarm_types);
    }
}


