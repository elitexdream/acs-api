<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Blender;

class TestController extends Controller
{
    public function store( Request $request)
    {
    	$blender_values = [1, 2, 3];
    	$blender = Blender::create([
    		'blender_id' => 3,
            'timestamp' => 1604599267,
            'values' => json_encode($blender_values)
        ]);

        return $blender;
    }
}
