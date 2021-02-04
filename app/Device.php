<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Device extends Model
{
    protected $fillable = [
        'device_id',
        'name',
        'customer_assigned_name',
        'serial_number',
        'imei',
        'lan_mac_address',
        'iccid',
        'carrier',
        'registered',
        'public_ip_sim',
        'sim_status',
        'location_id',
        'zone_id',
        'checkin'
    ];

    public function configuration() {
        return $this->belongsTo('App\Machine', 'machine_id');
    }

    public function notes() {
        return $this->hasMany('App\Note');
    }

    public function isRunning() {
        $config = $this->configuration;

        if(!$config) return false;

        $tag_id = 0;

        switch ($config->id) {
            case MACHINE_BD_BATCH_BLENDER:
                $tag_id = 9;
                break;
            case MACHINE_ACCUMETER_OVATION_CONTINUOUS_BLENDER:
                $tag_id = 10;
                break;
            case MACHINE_GH_GRAVIMETRIC_EXTRUSION_CONTROL_HOPPER:
                $tag_id = 13;
                break;
            case MACHINE_GH_F_GRAVIMETRIC_ADDITIVE_FEEDER:
                $tag_id = 11;
                break;
            case MACHINE_VTC_PLUS_CONVEYING_SYSTEM:
                $tag_id = 10;
                break;
            case MACHINE_NGX_DRYER:
                $tag_id = 36;
                break;
            case MACHINE_NGX_NOMAD_DRYER:
                $tag_id = 28;
                break;
            case MACHINE_T50_CENTRAL_GRANULATOR:
                $tag_id = 9;
                break;
            case MACHINE_GP_PORTABLE_CHILLER:
                $tag_id = 4;
                break;
            case MACHINE_HE_CENTRAL_CHILLER:
                $tag_id = 194;
                break;
            default:
                break;
        }

        if($tag_id) {
            $running_object = DB::table('runnings')->where('device_id', $this->serial_number)->where('tag_id', $tag_id)->latest('timestamp')->first();

            if($running_object) {
                return json_decode($running_object->values)[0];
            }
        }

        return false;
    }

    public function teltonikaConfiguration() {
        return TeltonikaConfiguration::where('teltonika_id', $this->serial_number)->first();
    }
}
