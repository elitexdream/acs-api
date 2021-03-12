<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MachineTag;

class MachineTagController extends Controller
{
    public function getMachineTags($machine_id) {
    	$tags = MachineTag::where('configuration_id', $machine_id)->get();

    	return response()->json(compact('tags'));
    }
}
