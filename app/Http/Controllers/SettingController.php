<?php

namespace App\Http\Controllers;

use App\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function getSetting(Request $request)
    {
        $value = Setting::all();
        
        return response()->json([
    		'value' => $value
        ]);
    }

    public function uploadLogo(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $file_name = $file->getClientOriginalName();
            $file->storeAs('assets', $file_name);

            $logo_filename = Setting::where('type', 'logo_file_name')->first();
            if (!$logo_filename) {
                Setting::create([
                    'type' => 'logo_file_name',
                    'value' => $file_name
                ]);
            } else {
                $logo_filename->value = $file_name;
                $logo_filename->save();
            }
            /* Storage::disk('local')->put($file_name, $file); */
        } else {
            return response()->json(['error'=>'File not exist!']);
        }
        return response()->json(['filename'=>$file_name, 'success'=>'Uploaded Successfully.']);
    }

    public function downloadLogo()
    {
        $logo_filename = Setting::where('type', 'logo_file_name')->first();
        if (!$logo_filename) {
            return response()->json(['error'=>'File not exist!']);
        }
        $path = '../storage/app/assets/' . $logo_filename->value;
        $header = [
            'Content-Type' => 'application/*',
        ];
        return response()->download($path, $logo_filename->value, $header);
    }

    public function setPrivateColors(Request $request)
    {
        $private_color_primary = Setting::where('type', 'private_color_primary')->first();
        if(!$private_color_primary) {
            Setting::create([
                'type' => 'private_color_primary',
                'value' => $request->colors[0]
            ]);
        } else {
            $private_color_primary->value = $request->colors[0];
            $private_color_primary->save();
        }

        if (count($request->colors) >= 2) {
            $private_color_accent = Setting::where('type', 'private_color_accent')->first();
            if(!$private_color_accent) {
                Setting::create([
                    'type' => 'private_color_accent',
                    'value' => $request->colors[1]
                ]);
                Setting::create([
                    'type' => 'private_color_background',
                    'value' => $request->colors[2]
                ]);
            } else {
                $private_color_accent->value = $request->colors[1];
                $private_color_accent->save();
                $private_color_background = Setting::where('type', 'private_color_background')->first();
                $private_color_background->value = $request->colors[2];
                $private_color_background->save();
            } 
        }

        return response()->json([
    		'private_colors' => $request->colors
        ]);
    }
}
