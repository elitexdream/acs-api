<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\SimStatus;

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
            case 1:
                $tag_id = 9;
                break;
            case 2:
                $tag_id = 10;
                break;
            case 3:
                $tag_id = 13;
                break;
            case 4:
                $tag_id = 11;
                break;
            case 5:
                $tag_id = 10;
                break;
            case 7:
                $tag_id = 28;
                break;
            default:
                break;
        }

        if($tag_id) {
            $running_object = DeviceData::where('device_id', $this->serial_number)->where('tag_id', $tag_id)->latest('timestamp')->first();

            if($running_object) {
                return json_decode($running_object->values)[0];
            }
        }

        return false;
    }
}
