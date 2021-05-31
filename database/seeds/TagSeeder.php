<?php

use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tags')->delete();

        $tags = [
            [ 'tag_name' => 'device_type', 'configuration_id' => MACHINE_BD_BATCH_BLENDER, 'tag_id' => 1 ],
            [ 'tag_name' => 'device_type', 'configuration_id' => MACHINE_ACCUMETER_OVATION_CONTINUOUS_BLENDER, 'tag_id' => 1 ],
            [ 'tag_name' => 'device_type', 'configuration_id' => MACHINE_GH_GRAVIMETRIC_EXTRUSION_CONTROL_HOPPER, 'tag_id' => 1 ],
            [ 'tag_name' => 'device_type', 'configuration_id' => MACHINE_GH_F_GRAVIMETRIC_ADDITIVE_FEEDER, 'tag_id' => 1 ],
            [ 'tag_name' => 'device_type', 'configuration_id' => MACHINE_VTC_PLUS_CONVEYING_SYSTEM, 'tag_id' => 1 ],
            [ 'tag_name' => 'device_type', 'configuration_id' => MACHINE_NGX_DRYER, 'tag_id' => 1 ],
            [ 'tag_name' => 'device_type', 'configuration_id' => MACHINE_NGX_NOMAD_DRYER, 'tag_id' => 1 ],
            [ 'tag_name' => 'device_type', 'configuration_id' => MACHINE_T50_CENTRAL_GRANULATOR, 'tag_id' => 1 ],
            [ 'tag_name' => 'device_type', 'configuration_id' => MACHINE_GP_PORTABLE_CHILLER, 'tag_id' => 120 ],
            [ 'tag_name' => 'device_type', 'configuration_id' => MACHINE_HE_CENTRAL_CHILLER, 'tag_id' => 229 ],

            [ 'tag_name' => 'software_version', 'configuration_id' => MACHINE_BD_BATCH_BLENDER, 'tag_id' => 4 ],
            [ 'tag_name' => 'software_version', 'configuration_id' => MACHINE_ACCUMETER_OVATION_CONTINUOUS_BLENDER, 'tag_id' => 4 ],
            [ 'tag_name' => 'software_version', 'configuration_id' => MACHINE_GH_GRAVIMETRIC_EXTRUSION_CONTROL_HOPPER, 'tag_id' => 4 ],
            [ 'tag_name' => 'software_version', 'configuration_id' => MACHINE_GH_F_GRAVIMETRIC_ADDITIVE_FEEDER, 'tag_id' => 4 ],
            [ 'tag_name' => 'software_version', 'configuration_id' => MACHINE_VTC_PLUS_CONVEYING_SYSTEM, 'tag_id' => 4 ],
            [ 'tag_name' => 'software_version', 'configuration_id' => MACHINE_NGX_DRYER, 'tag_id' => 4 ],
            [ 'tag_name' => 'software_version', 'configuration_id' => MACHINE_NGX_NOMAD_DRYER, 'tag_id' => 4 ],
            [ 'tag_name' => 'software_version', 'configuration_id' => MACHINE_T50_CENTRAL_GRANULATOR, 'tag_id' => 4 ],
            [ 'tag_name' => 'software_version', 'configuration_id' => MACHINE_GP_PORTABLE_CHILLER, 'tag_id' => 99 ],
            [ 'tag_name' => 'software_version', 'configuration_id' => MACHINE_HE_CENTRAL_CHILLER, 'tag_id' => 4 ],

            [ 'tag_name' => 'software_build', 'configuration_id' => MACHINE_BD_BATCH_BLENDER, 'tag_id' => 5 ],
            [ 'tag_name' => 'software_build', 'configuration_id' => MACHINE_ACCUMETER_OVATION_CONTINUOUS_BLENDER, 'tag_id' => 5 ],
            [ 'tag_name' => 'software_build', 'configuration_id' => MACHINE_GH_GRAVIMETRIC_EXTRUSION_CONTROL_HOPPER, 'tag_id' => 5 ],
            [ 'tag_name' => 'software_build', 'configuration_id' => MACHINE_GH_F_GRAVIMETRIC_ADDITIVE_FEEDER, 'tag_id' => 5 ],
            [ 'tag_name' => 'software_build', 'configuration_id' => MACHINE_VTC_PLUS_CONVEYING_SYSTEM, 'tag_id' => 5 ],
            [ 'tag_name' => 'software_build', 'configuration_id' => MACHINE_NGX_DRYER, 'tag_id' => 5 ],
            [ 'tag_name' => 'software_build', 'configuration_id' => MACHINE_NGX_NOMAD_DRYER, 'tag_id' => 5 ],
            [ 'tag_name' => 'software_build', 'configuration_id' => MACHINE_T50_CENTRAL_GRANULATOR, 'tag_id' => 5 ],
            [ 'tag_name' => 'software_build', 'configuration_id' => MACHINE_GP_PORTABLE_CHILLER, 'tag_id' => 101 ],

            [ 'tag_name' => 'serial_number_month', 'configuration_id' => MACHINE_BD_BATCH_BLENDER, 'tag_id' => 6 ],
            [ 'tag_name' => 'serial_number_month', 'configuration_id' => MACHINE_ACCUMETER_OVATION_CONTINUOUS_BLENDER, 'tag_id' => 6 ],
            [ 'tag_name' => 'serial_number_month', 'configuration_id' => MACHINE_GH_GRAVIMETRIC_EXTRUSION_CONTROL_HOPPER, 'tag_id' => 6 ],
            [ 'tag_name' => 'serial_number_month', 'configuration_id' => MACHINE_GH_F_GRAVIMETRIC_ADDITIVE_FEEDER, 'tag_id' => 6 ],
            [ 'tag_name' => 'serial_number_month', 'configuration_id' => MACHINE_VTC_PLUS_CONVEYING_SYSTEM, 'tag_id' => 6 ],
            [ 'tag_name' => 'serial_number_month', 'configuration_id' => MACHINE_NGX_DRYER, 'tag_id' => 6 ],
            [ 'tag_name' => 'serial_number_month', 'configuration_id' => MACHINE_NGX_NOMAD_DRYER, 'tag_id' => 6 ],
            [ 'tag_name' => 'serial_number_month', 'configuration_id' => MACHINE_T50_CENTRAL_GRANULATOR, 'tag_id' => 6 ],
            [ 'tag_name' => 'serial_number_month', 'configuration_id' => MACHINE_GP_PORTABLE_CHILLER, 'tag_id' => 102 ],

            [ 'tag_name' => 'serial_number_year', 'configuration_id' => MACHINE_BD_BATCH_BLENDER, 'tag_id' => 7 ],
            [ 'tag_name' => 'serial_number_year', 'configuration_id' => MACHINE_ACCUMETER_OVATION_CONTINUOUS_BLENDER, 'tag_id' => 7 ],
            [ 'tag_name' => 'serial_number_year', 'configuration_id' => MACHINE_GH_GRAVIMETRIC_EXTRUSION_CONTROL_HOPPER, 'tag_id' => 7 ],
            [ 'tag_name' => 'serial_number_year', 'configuration_id' => MACHINE_GH_F_GRAVIMETRIC_ADDITIVE_FEEDER, 'tag_id' => 7 ],
            [ 'tag_name' => 'serial_number_year', 'configuration_id' => MACHINE_VTC_PLUS_CONVEYING_SYSTEM, 'tag_id' => 7 ],
            [ 'tag_name' => 'serial_number_year', 'configuration_id' => MACHINE_NGX_DRYER, 'tag_id' => 7 ],
            [ 'tag_name' => 'serial_number_year', 'configuration_id' => MACHINE_NGX_NOMAD_DRYER, 'tag_id' => 7 ],
            [ 'tag_name' => 'serial_number_year', 'configuration_id' => MACHINE_T50_CENTRAL_GRANULATOR, 'tag_id' => 7 ],
            [ 'tag_name' => 'serial_number_year', 'configuration_id' => MACHINE_GP_PORTABLE_CHILLER, 'tag_id' => 103 ],

            [ 'tag_name' => 'serial_number_unit', 'configuration_id' => MACHINE_BD_BATCH_BLENDER, 'tag_id' => 8 ],
            [ 'tag_name' => 'serial_number_unit', 'configuration_id' => MACHINE_ACCUMETER_OVATION_CONTINUOUS_BLENDER, 'tag_id' => 8 ],
            [ 'tag_name' => 'serial_number_unit', 'configuration_id' => MACHINE_GH_GRAVIMETRIC_EXTRUSION_CONTROL_HOPPER, 'tag_id' => 8 ],
            [ 'tag_name' => 'serial_number_unit', 'configuration_id' => MACHINE_GH_F_GRAVIMETRIC_ADDITIVE_FEEDER, 'tag_id' => 8 ],
            [ 'tag_name' => 'serial_number_unit', 'configuration_id' => MACHINE_VTC_PLUS_CONVEYING_SYSTEM, 'tag_id' => 8 ],
            [ 'tag_name' => 'serial_number_unit', 'configuration_id' => MACHINE_NGX_DRYER, 'tag_id' => 8 ],
            [ 'tag_name' => 'serial_number_unit', 'configuration_id' => MACHINE_NGX_NOMAD_DRYER, 'tag_id' => 8 ],
            [ 'tag_name' => 'serial_number_unit', 'configuration_id' => MACHINE_T50_CENTRAL_GRANULATOR, 'tag_id' => 8 ],
            [ 'tag_name' => 'serial_number_unit', 'configuration_id' => MACHINE_GP_PORTABLE_CHILLER, 'tag_id' => 104 ],

            [ 'tag_name' => 'capacity_utilization', 'configuration_id' => MACHINE_BD_BATCH_BLENDER, 'tag_id' => 2 ],
            [ 'tag_name' => 'capacity_utilization', 'configuration_id' => MACHINE_ACCUMETER_OVATION_CONTINUOUS_BLENDER, 'tag_id' => 2 ],
            [ 'tag_name' => 'capacity_utilization', 'configuration_id' => MACHINE_GH_GRAVIMETRIC_EXTRUSION_CONTROL_HOPPER, 'tag_id' => 2 ],
            [ 'tag_name' => 'capacity_utilization', 'configuration_id' => MACHINE_GH_F_GRAVIMETRIC_ADDITIVE_FEEDER, 'tag_id' => 2 ],
            [ 'tag_name' => 'capacity_utilization', 'configuration_id' => MACHINE_VTC_PLUS_CONVEYING_SYSTEM, 'tag_id' => 2 ],
            [ 'tag_name' => 'capacity_utilization', 'configuration_id' => MACHINE_NGX_DRYER, 'tag_id' => 2 ],
            [ 'tag_name' => 'capacity_utilization', 'configuration_id' => MACHINE_NGX_NOMAD_DRYER, 'tag_id' => 2 ],
            [ 'tag_name' => 'capacity_utilization', 'configuration_id' => MACHINE_T50_CENTRAL_GRANULATOR, 'tag_id' => 2 ],
            [ 'tag_name' => 'capacity_utilization', 'configuration_id' => MACHINE_GP_PORTABLE_CHILLER, 'tag_id' => 105 ],
            [ 'tag_name' => 'capacity_utilization', 'configuration_id' => MACHINE_HE_CENTRAL_CHILLER, 'tag_id' => 230 ],
            [ 'tag_name' => 'capacity_utilization', 'configuration_id' => MACHINE_TRUETEMP_TCU, 'tag_id' => 28 ],
            [ 'tag_name' => 'capacity_utilization', 'configuration_id' => MACHINE_TRUETEMP_TCU, 'tag_id' => 29 ],

            [ 'tag_name' => 'energy_consumption', 'configuration_id' => MACHINE_BD_BATCH_BLENDER, 'tag_id' => 3 ],
            [ 'tag_name' => 'energy_consumption', 'configuration_id' => MACHINE_ACCUMETER_OVATION_CONTINUOUS_BLENDER, 'tag_id' => 3 ],
            [ 'tag_name' => 'energy_consumption', 'configuration_id' => MACHINE_GH_GRAVIMETRIC_EXTRUSION_CONTROL_HOPPER, 'tag_id' => 3 ],
            [ 'tag_name' => 'energy_consumption', 'configuration_id' => MACHINE_GH_F_GRAVIMETRIC_ADDITIVE_FEEDER, 'tag_id' => 3 ],
            [ 'tag_name' => 'energy_consumption', 'configuration_id' => MACHINE_VTC_PLUS_CONVEYING_SYSTEM, 'tag_id' => 3 ],
            [ 'tag_name' => 'energy_consumption', 'configuration_id' => MACHINE_NGX_DRYER, 'tag_id' => 3 ],
            [ 'tag_name' => 'energy_consumption', 'configuration_id' => MACHINE_NGX_NOMAD_DRYER, 'tag_id' => 3 ],
            [ 'tag_name' => 'energy_consumption', 'configuration_id' => MACHINE_T50_CENTRAL_GRANULATOR, 'tag_id' => 3 ],
            [ 'tag_name' => 'energy_consumption', 'configuration_id' => MACHINE_GP_PORTABLE_CHILLER, 'tag_id' => 106 ],
            [ 'tag_name' => 'energy_consumption', 'configuration_id' => MACHINE_HE_CENTRAL_CHILLER, 'tag_id' => 231 ],

            [ 'tag_name' => 'running', 'configuration_id' => MACHINE_BD_BATCH_BLENDER, 'tag_id' => 9 ],
            [ 'tag_name' => 'running', 'configuration_id' => MACHINE_ACCUMETER_OVATION_CONTINUOUS_BLENDER, 'tag_id' => 10 ],
            [ 'tag_name' => 'running', 'configuration_id' => MACHINE_GH_GRAVIMETRIC_EXTRUSION_CONTROL_HOPPER, 'tag_id' => 9 ],
            [ 'tag_name' => 'running', 'configuration_id' => MACHINE_GH_F_GRAVIMETRIC_ADDITIVE_FEEDER, 'tag_id' => 8 ],
            [ 'tag_name' => 'running', 'configuration_id' => MACHINE_VTC_PLUS_CONVEYING_SYSTEM, 'tag_id' => 10 ],
            [ 'tag_name' => 'running', 'configuration_id' => MACHINE_NGX_DRYER, 'tag_id' => 36 ],
            [ 'tag_name' => 'running', 'configuration_id' => MACHINE_NGX_NOMAD_DRYER, 'tag_id' => 28 ],
            [ 'tag_name' => 'running', 'configuration_id' => MACHINE_T50_CENTRAL_GRANULATOR, 'tag_id' => 9 ],
            [ 'tag_name' => 'running', 'configuration_id' => MACHINE_GP_PORTABLE_CHILLER, 'tag_id' => 4 ],
            [ 'tag_name' => 'running', 'configuration_id' => MACHINE_HE_CENTRAL_CHILLER, 'tag_id' => 194 ],
            [ 'tag_name' => 'running', 'configuration_id' => MACHINE_TRUETEMP_TCU, 'tag_id' => 40 ],

            [ 'tag_name' => 'idle', 'configuration_id' => MACHINE_BD_BATCH_BLENDER, 'tag_id' => 53 ],
            [ 'tag_name' => 'idle', 'configuration_id' => MACHINE_ACCUMETER_OVATION_CONTINUOUS_BLENDER, 'tag_id' => 59 ],
            [ 'tag_name' => 'idle', 'configuration_id' => MACHINE_GH_GRAVIMETRIC_EXTRUSION_CONTROL_HOPPER, 'tag_id' => 35 ],
            [ 'tag_name' => 'idle', 'configuration_id' => MACHINE_GH_F_GRAVIMETRIC_ADDITIVE_FEEDER, 'tag_id' => 43 ],
            [ 'tag_name' => 'idle', 'configuration_id' => MACHINE_VTC_PLUS_CONVEYING_SYSTEM, 'tag_id' => 24 ],
            [ 'tag_name' => 'idle', 'configuration_id' => MACHINE_NGX_DRYER, 'tag_id' => 73 ],
            [ 'tag_name' => 'idle', 'configuration_id' => MACHINE_NGX_NOMAD_DRYER, 'tag_id' => 66 ],
            [ 'tag_name' => 'idle', 'configuration_id' => MACHINE_T50_CENTRAL_GRANULATOR, 'tag_id' => 50 ],
            [ 'tag_name' => 'idle', 'configuration_id' => MACHINE_GP_PORTABLE_CHILLER, 'tag_id' => 121 ],
            [ 'tag_name' => 'idle', 'configuration_id' => MACHINE_HE_CENTRAL_CHILLER, 'tag_id' => 294 ],
            
        ];
        DB::table('tags')->insert($tags);
    }
}
