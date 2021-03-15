<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MachineTag;
use App\AlarmType;

class MachineTagController extends Controller
{
    public function getMachineTags($machine_id) {
    	$tags = MachineTag::where('configuration_id', $machine_id)->orderBy('name')->get();
    	$alarm_tags = AlarmType::where('machine_id', $machine_id)->orderBy('name')->get();

    	$tags = $tags->merge($alarm_tags);

    	return response()->json(compact('tags'));
    }
}
