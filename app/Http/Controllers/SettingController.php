<?php

namespace App\Http\Controllers;

use App\Setting;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Validator;

class SettingController extends Controller
{
    public function getSetting(Request $request)
    {
        $value = Setting::all();
        
        return response()->json([
    		'value' => $value
        ]);
    }

    /*
        Get basic app settings - logo, primary color..
    */
    public function appSettings() {
        $settings = [];

        $logo = Setting::where('type', 'logo_filepath')->first();

        if($logo) {
            $settings['logo'] = $logo->value;
        }

        return response()->json(compact('settings'));
    }

    public function resetSettings()
    {
        Setting::where('type', 'logo_filepath')
                    ->orWhere('type', 'auth_background_filepath')
                    ->orWhere('type', 'LIKE', 'private_color_%')
                    ->delete();

        return response()->json([
            'success'=>'Reset Successfully.'
        ]);
    }

    public function uploadLogo(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'logo' => 'required|image'
        ]);

        if ($validator->fails())
        {
            return response()->json(['error'=>$validator->errors()], 422);            
        }

        $file = $request->file('logo');
        $file_name = $file->getClientOriginalName();
        $file->storeAs('', $file_name, 'public_uploads');
        $file_path = asset('assets/app/img'). '/' . $file_name;
        $logo_filename = Setting::where('type', 'logo_filepath')->first();
        if (!$logo_filename) {
            Setting::create([
                'type' => 'logo_filepath',
                'value' => $file_path
            ]);
        } else {
            $logo_filename->value = $file_path;
            $logo_filename->save();
        }

        return response()->json([
            'filepath'=>$file_path,
            'success'=>'Uploaded Successfully.'
        ]);
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

    public function updateAuthBackground(Request $request)
    {
        $client = new Client();
        $keywords = [
            'Industry 4.0',
            'IOT',
            'factory'
        ];
        $API_KEY = 'a5b68mEtkbahjcG-fvv-R8jw8_YM6lHhOmbloIhvVwE';  // suck@machinecdn.com
        try {
            $results = [];
            foreach($keywords as $keyword) {
                $url = 'https://api.unsplash.com/search/photos?query=' . $keyword . '&client_id=' . $API_KEY;
                $response = $client->get(
                    urldecode($url)
                );
                $results = array_merge($results, json_decode($response->getBody()->getContents())->results);
            }
            
            $idx = rand(0, count($results) - 1);
            $image_url = $results[$idx]->urls->regular;            
            $auth_background = Setting::where('type', 'auth_background_filepath')->first();
            if(!$auth_background) {
                Setting::create([
                    'type' => 'auth_background_filepath',
                    'value' => $image_url
                ]);
            } else {
                $auth_background->value = $image_url;
                $auth_background->save();
            }

            return response()->json([
                'filepath' => $image_url
            ]);
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            return response()->json(json_decode($e->getResponse()->getBody()->getContents(), true), $e->getCode());
        }
    }
}