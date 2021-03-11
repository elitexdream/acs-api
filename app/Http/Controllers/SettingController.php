<?php

namespace App\Http\Controllers;

use App\Setting;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Validator;

use App\Machine;

class SettingController extends Controller
{
    public function getSetting()
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
                    ->delete();

        Setting::where('type', 'color_background')->first()->update([
            'value' => '#ffffff'
        ]);
        Setting::where('type', 'color_primary')->first()->update([
            'value' => '#092954'
        ]);
        Setting::where('type', 'color_secondary')->first()->update([
            'value' => '#508FF0'
        ]);
        Setting::where('type', 'color_accent')->first()->update([
            'value' => '#003066'
        ]);
        Setting::where('type', 'color_surface')->first()->update([
            'value' => '#f2f5f8'
        ]);

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

    public function uploadImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image'
        ]);

        if ($validator->fails())
        {
            return response()->json(['error'=>$validator->errors()], 422);            
        }

        $file = $request->file('image');
        $file_name = $file->getClientOriginalName();
        $file->storeAs('', $file_name, 'public_uploads');
        $file_path = asset('assets/app/img'). '/' . $file_name;
        $image_filename = Setting::where('type', 'auth_background_filepath')->first();
        if (!$image_filename) {
            Setting::create([
                'type' => 'auth_background_filepath',
                'value' => $file_path
            ]);
        } else {
            $image_filename->value = $file_path;
            $image_filename->save();
        }

        return response()->json([
            'filepath'=>$file_path,
            'message'=>'Uploaded Successfully.'
        ]);
    }

    public function setProductInfo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'productName' => 'required',
            'productVersion' => 'required'
        ]);

        if ($validator->fails())
        {
            return response()->json(['error'=>$validator->errors()], 422);            
        }

        $product_name = Setting::where('type', 'product_name')->first();
        if (!$product_name) {
            Setting::create([
                'type' => 'product_name',
                'value' => $request->productName
            ]);
        } else {
            $product_name->value = $request->productName;
            $product_name->save();
        }

        $product_version = Setting::where('type', 'product_version')->first();
        if (!$product_version) {
            Setting::create([
                'type' => 'product_version',
                'value' => $request->productVersion
            ]);
        } else {
            $product_version->value = $request->productVersion;
            $product_version->save();
        }

        return response()->json([
            'product_name'=>$product_name->value,
            'product_version'=>$product_version->value,
            'message' => 'Updated Successfully'
        ]);
    }

    public function setPageTitle(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pageTitle' => 'required'
        ]);

        if ($validator->fails())
        {
            return response()->json(['error'=>$validator->errors()], 422);            
        }

        $page_title = Setting::where('type', 'page_title')->first();
        if (!$page_title) {
            $page_title = Setting::create([
                'type' => 'page_title',
                'value' => $request->pageTitle
            ]);
        } else {
            $page_title->value = $request->pageTitle;
            $page_title->save();
        }

        return response()->json([
            'page_title' => $request->pageTitle,
            'message' => 'Updated Successfully'
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

    public function applyWebsiteColors(Request $request)
    {
        $colors = $request->colors;

        foreach($colors as $color) {
            $setting = Setting::where('type', $color['key'])->first();

            if($setting)
                $setting->update([
                    'value' => $color['color']
                ]);
        }

        return response()->json([
            'message' => 'Successfully updated'
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