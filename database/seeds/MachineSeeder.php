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
                'id' => 1,
                'name' => 'BD Batch Blender',
                'full_json' => json_encode($json_bd_batch_blender)
            ], [
                'id' => 2,
                'name' => 'Accumeter Ovation Continuous Blender',
                'full_json' => json_encode($json_accumeter_ovation_continuous_blender)
            ], [
                'id' => 3,
                'name' => 'GH Gravimetric Extrusion Control Hopper',
                'full_json' => json_encode($json_gh_gravimetric_extrusion_control_hopper)
            ], [
                'id' => 4,
                'name' => 'GH-F Gravimetric Additive Feeder',
                'full_json' => json_encode($json_gh_f_gravimetric_additive_feeder)
            ], [
                'id' => 5,
                'name' => 'VTC Plus Conveying System',
                'full_json' => json_encode($json_vtc_plus_conveying_system)
            ], [
                'id' => 6,
                'name' => 'NGX Dryer',
                'full_json' => json_encode($json_ngx_dryer)
            ], [
                'id' => 7,
                'name' => 'NGX Nomad Dryer',
                'full_json' => json_encode($json_ngx_nomad_dryer)
            ], [
                'id' => 8,
                'name' => 'T50 Central Granulator',
                'full_json' => json_encode($json_t50_central_granulator)
            ], [
                'id' => 9,
                'name' => 'GP Portable Chiller',
                'full_json' => json_encode($json_gp_portable_chiller)
            ], [
                'id' => 10,
                'name' => 'HE Central Chiller',
                'full_json' => json_encode($json_he_central_chiller)
            ], [
                'id' => 11,
                'name' => 'TrueTemp TCU',
                'full_json' => json_encode($json_truetemp_tcu)
            ]
        ];
        DB::table('machines')->insert($machines);
    }
}