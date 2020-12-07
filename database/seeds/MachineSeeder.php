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

        $json_bd_batch_blender = json_decode(file_get_contents($path_bd_batch_blender), true);
        $json_accumeter_ovation_continuous_blender = json_decode(file_get_contents($path_accumeter_ovation_continuous_blender), true);
        $json_gh_gravimetric_extrusion_control_hopper = json_decode(file_get_contents($path_gh_gravimetric_extrusion_control_hopper), true);
        $json_gh_f_gravimetric_additive_feeder = json_decode(file_get_contents($path_gh_f_gravimetric_additive_feeder), true);
        $json_vtc_plus_conveying_system = json_decode(file_get_contents($path_vtc_plus_conveying_system), true);
        $json_ngx_dryer = json_decode(file_get_contents($path_ngx_dryer), true);
        $json_ngx_nomad_dryer = json_decode(file_get_contents($path_ngx_nomad_dryer), true);
        $json_t50_central_granulator = json_decode(file_get_contents($path_t50_central_granulator), true);

        $machines = [ [
                'id' => 1,
                'name' => 'BD Batch Blender',
                'plc_ip' => $json_bd_batch_blender['plc_ip'],
                'format' => $json_bd_batch_blender['format'],
                'batch_size' => $json_bd_batch_blender['batch_size'],
                'batch_timeout' => $json_bd_batch_blender['batch_timeout'],
                'config_hash' => $json_bd_batch_blender['config_hash'],
                'full_json' => json_encode($json_bd_batch_blender)
            ], [
                'id' => 2,
                'name' => 'Accumeter Ovation Continuous Blender',
                'plc_ip' => $json_accumeter_ovation_continuous_blender['plc_ip'],
                'format' => $json_accumeter_ovation_continuous_blender['format'],
                'batch_size' => $json_accumeter_ovation_continuous_blender['batch_size'],
                'batch_timeout' => $json_accumeter_ovation_continuous_blender['batch_timeout'],
                'config_hash' => $json_accumeter_ovation_continuous_blender['config_hash'],
                'full_json' => json_encode($json_accumeter_ovation_continuous_blender)
            ], [
                'id' => 3,
                'name' => 'GH Gravimetric Extrusion Control Hopper',
                'plc_ip' => $json_gh_gravimetric_extrusion_control_hopper['plc_ip'],
                'format' => $json_gh_gravimetric_extrusion_control_hopper['format'],
                'batch_size' => $json_gh_gravimetric_extrusion_control_hopper['batch_size'],
                'batch_timeout' => $json_gh_gravimetric_extrusion_control_hopper['batch_timeout'],
                'config_hash' => $json_gh_gravimetric_extrusion_control_hopper['config_hash'],
                'full_json' => json_encode($json_gh_gravimetric_extrusion_control_hopper)
            ], [
                'id' => 4,
                'name' => 'GH-F Gravimetric Additive Feeder',
                'plc_ip' => $json_gh_f_gravimetric_additive_feeder['plc_ip'],
                'format' => $json_gh_f_gravimetric_additive_feeder['format'],
                'batch_size' => $json_gh_f_gravimetric_additive_feeder['batch_size'],
                'batch_timeout' => $json_gh_f_gravimetric_additive_feeder['batch_timeout'],
                'config_hash' => $json_gh_f_gravimetric_additive_feeder['config_hash'],
                'full_json' => json_encode($json_gh_f_gravimetric_additive_feeder)
            ], [
                'id' => 5,
                'name' => 'VTC Plus Conveying System',
                'plc_ip' => $json_vtc_plus_conveying_system['plc_ip'],
                'format' => $json_vtc_plus_conveying_system['format'],
                'batch_size' => $json_vtc_plus_conveying_system['batch_size'],
                'batch_timeout' => $json_vtc_plus_conveying_system['batch_timeout'],
                'config_hash' => $json_vtc_plus_conveying_system['config_hash'],
                'full_json' => json_encode($json_vtc_plus_conveying_system)
            ], [
                'id' => 6,
                'name' => 'NGX Dryer',
                'plc_ip' => $json_ngx_dryer['plc_ip'],
                'format' => $json_ngx_dryer['format'],
                'batch_size' => $json_ngx_dryer['batch_size'],
                'batch_timeout' => $json_ngx_dryer['batch_timeout'],
                'config_hash' => $json_ngx_dryer['config_hash'],
                'full_json' => json_encode($json_ngx_dryer)
            ], [
                'id' => 7,
                'name' => 'NGX Nomad Dryer',
                'plc_ip' => $json_ngx_nomad_dryer['plc_ip'],
                'format' => $json_ngx_nomad_dryer['format'],
                'batch_size' => $json_ngx_nomad_dryer['batch_size'],
                'batch_timeout' => $json_ngx_nomad_dryer['batch_timeout'],
                'config_hash' => $json_ngx_nomad_dryer['config_hash'],
                'full_json' => json_encode($json_ngx_nomad_dryer)
            ], [
                'id' => 8,
                'name' => 'T50 Central Granulator',
                'plc_ip' => $json_t50_central_granulator['plc_ip'],
                'format' => $json_t50_central_granulator['format'],
                'batch_size' => $json_t50_central_granulator['batch_size'],
                'batch_timeout' => $json_t50_central_granulator['batch_timeout'],
                'config_hash' => $json_t50_central_granulator['config_hash'],
                'full_json' => json_encode($json_t50_central_granulator)
            ]
        ];
        DB::table('machines')->insert($machines);
    }
}