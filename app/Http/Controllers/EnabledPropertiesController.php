<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class EnabledPropertiesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user('api');

        $properties = $user->enabledProperties;

        return response()->json(compact('properties'));
    }
}
