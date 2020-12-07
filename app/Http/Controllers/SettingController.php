<?php

namespace App\Http\Controllers;

use App\Setting;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

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

    public function updateAuthBackground(Request $request)
    {
        $client = new Client();
        try {
            $url = 'https://api.unsplash.com/search/photos?query=Industry 4.0&client_id=a5b68mEtkbahjcG-fvv-R8jw8_YM6lHhOmbloIhvVwE';
            $response = $client->get(
                urldecode($url)
            );            
            $results = json_decode($response->getBody()->getContents())->results;
            
            $url = 'https://api.unsplash.com/search/photos?query=manufacturing&client_id=a5b68mEtkbahjcG-fvv-R8jw8_YM6lHhOmbloIhvVwE';
            $response = $client->get(
                urldecode($url)
            );
            $results = array_merge($results, json_decode($response->getBody()->getContents())->results);
            
            $idx = rand(0, count($results) - 1);
            $image_url = $results[$idx]->urls->regular;            
            $auth_background = Setting::where('type', 'auth_background')->first();
            if(!$auth_background) {
                Setting::create([
                    'type' => 'auth_background',
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