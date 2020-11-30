<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\City;

class CityController extends Controller
{
    public function citiesForState($state) {
    	$cities = City::select('id', 'zip', 'city')->where('state', $state)->orderBy('city')->get();

    	return response()->json($cities);
    }
}
