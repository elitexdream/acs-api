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

        $path_bd_batch_blender = storage_path() . "/json/plc_configs/BD_Batch_Blender.json";
        $path_accumeter_ovation_continuous_blender = storage_path() . "/json/plc_configs/Accumeter_Ovation_Continuous_Blender.json";
        $path_gh_gravimetric_extrusion_control_hopper = storage_path() . "/json/plc_configs/GH_Gravimetric_Extrusion_Control_Hopper.json";
        $path_gh_f_gravimetric_additive_feeder = storage_path() . "/json/plc_configs/GH-F_Gravimetric_Additive_Feeder.json";
        $path_vtc_plus_conveying_system = storage_path() . "/json/plc_configs/VTC_Plus_Conveying_System.json";
        $path_ngx_dryer = storage_path() . "/json/plc_configs/NGX_Dryer.json";
        $path_ngx_nomad_dryer = storage_path() . "/json/plc_configs/NGX_Nomad_Dryer.json";
        $path_t50_central_granulator = storage_path() . "/json/plc_configs/T50_Central_Granulator.json";
        $path_gp_portable_chiller = storage_path() . "/json/plc_configs/GP_Portable_Chiller.json";
        $path_he_central_chiller = storage_path() . "/json/plc_configs/HE_Central_Chiller.json";
        $path_truetemp_tcu = storage_path() . "/json/plc_configs/TrueTemp_TCU.json";

        $json_bd_batch_blender = json_decode(file_get_contents($path_bd_batch_blender), true);
        $json_accumeter_ovation_continuous_blender = json_decode(file_get_contents($path_accumeter_ovation_continuous_blender), true);
        $json_gh_gravimetric_extrusion_control_hopper = json_decode(file_get_contents($path_gh_gravimetric_extrusion_control_hopper), true);
        $json_gh_f_gravimetric_additive_feeder = json_decode(file_get_contents($path_gh_f_gravimetric_additive_feeder), true);
        $json_vtc_plus_conveying_system = json_decode(file_get_contents($path_vtc_plus_conveying_system), true);
        $json_ngx_dryer = json_decode(file_get_contents($path_ngx_dryer), true);
        $json_ngx_nomad_dryer = json_decode(file_get_contents($path_ngx_nomad_dryer), true);
        $json_t50_central_granulator = json_decode(file_get_contents($path_t50_central_granulator), true);
        $json_gp_portable_chiller = json_decode(file_get_contents($path_gp_portable_chiller), true);
        $json_he_central_chiller = json_decode(file_get_contents($path_he_central_chiller), true);
        $json_truetemp_tcu = json_decode(file_get_contents($path_truetemp_tcu), true);

        $machines = [ [
                'id' => MACHINE_BD_BATCH_BLENDER,
                'name' => 'BD Batch Blender',
                'full_json' => json_encode($json_bd_batch_blender)
            ], [
                'id' => MACHINE_ACCUMETER_OVATION_CONTINUOUS_BLENDER,
                'name' => 'Accumeter Ovation Continuous Blender',
                'full_json' => json_encode($json_accumeter_ovation_continuous_blender)
            ], [
                'id' => MACHINE_GH_GRAVIMETRIC_EXTRUSION_CONTROL_HOPPER,
                'name' => 'GH Gravimetric Extrusion Control Hopper',
                'full_json' => json_encode($json_gh_gravimetric_extrusion_control_hopper)
            ], [
                'id' => MACHINE_GH_F_GRAVIMETRIC_ADDITIVE_FEEDER,
                'name' => 'GH-F Gravimetric Additive Feeder',
                'full_json' => json_encode($json_gh_f_gravimetric_additive_feeder)
            ], [
                'id' => MACHINE_VTC_PLUS_CONVEYING_SYSTEM,
                'name' => 'VTC Plus Conveying System',
                'full_json' => json_encode($json_vtc_plus_conveying_system)
            ], [
                'id' => MACHINE_NGX_DRYER,
                'name' => 'NGX Dryer',
                'full_json' => json_encode($json_ngx_dryer)
            ], [
                'id' => MACHINE_NGX_NOMAD_DRYER,
                'name' => 'NGX Nomad Dryer',
                'full_json' => json_encode($json_ngx_nomad_dryer)
            ], [
                'id' => MACHINE_T50_CENTRAL_GRANULATOR,
                'name' => 'T50 Central Granulator',
                'full_json' => json_encode($json_t50_central_granulator)
            ], [
                'id' => MACHINE_GP_PORTABLE_CHILLER,
                'name' => 'GP Portable Chiller',
                'full_json' => json_encode($json_gp_portable_chiller)
            ], [
                'id' => MACHINE_HE_CENTRAL_CHILLER,
                'name' => 'HE Central Chiller',
                'full_json' => json_encode($json_he_central_chiller)
            ], [
                'id' => MACHINE_TRUETEMP_TCU,
                'name' => 'TrueTemp TCU',
                'full_json' => json_encode($json_truetemp_tcu)
            ]
        ];
        DB::table('machines')->insert($machines);
    }
}