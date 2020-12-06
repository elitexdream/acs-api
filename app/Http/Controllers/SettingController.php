<?php

namespace App\Http\Controllers;

use App\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function getSetting(Request $request)
    {
        $value = Setting::where('type', $request->type)->first();
        
        return response()->json([
    		'value' => $value
        ]);
    }

    public function setPrivateColor(Request $request)
    {
        $private_color = Setting::where('type', 'private_color')->first();
        if(!$private_color) {
            $private_color = Setting::create([
                'type' => 'private_color',
                'value' => $request->color
            ]);
        } else {
            $private_color->value = $request->color;
            $private_color->save();
        }

        return response()->json([
    		'private_color' => $private_color->value
        ]);
    }
}
