<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';

    protected $fillable = [
        'type', 'value'
    ];

    public $timestamps = true;

    const TYPE_IS_ALL_DEVICES_VISIBLE = 'is_all_devices_visible';

    public function getTypeVisibleValue() {
       $data = self::where('type', static::TYPE_IS_ALL_DEVICES_VISIBLE)->first();

       return ($data && $data->value) ? $data->value : null;
    }
}
