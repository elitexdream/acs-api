<?php

namespace App\Http\Controllers;

use App\Swatch;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class SwatchController extends Controller
{
    public function grabColors(Request $request)
    {
        $client = new Client();
        $swatch = Swatch::where('site_url', $request->url)->first();
        if(!$swatch) {
            try {
                $url = 'http://www.colorfyit.com/api/swatches/list.json?url=' . $request->url . '&discover=:discover]';
                $response = $client->post(
                    urldecode($url)
                );
                $colors = json_decode($response->getBody()->getContents())->colors;
                Swatch::create([
                    'site_url' => $request->url,
                    'colors' => json_encode($colors)
                ]);
            } catch (\GuzzleHttp\Exception\BadResponseException $e) {
                return response()->json(json_decode($e->getResponse()->getBody()->getContents(), true), $e->getCode());
            }
        } else {
            $colors = json_decode($swatch->colors);
        }

        return response()->json([
    		'colors' => $colors
        ]);
    }
}
