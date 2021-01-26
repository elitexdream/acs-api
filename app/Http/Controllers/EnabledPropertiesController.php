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

        $properties = $user->enabledProperties();

        return response()->json(compact('properties'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = $request->user('api');

        $row = DB::table('enabled_properties')->where('user_id', $user->id)->where('machine_id', $id);
        if($row->count()) {
            $ids = [];
            $existing_ids = json_decode($row->first()->property_ids);

            if($request->isImportant) {
                foreach ($existing_ids as $value) {
                    if($value > 100) array_push($ids, $value);
                }
                $ids = array_merge($ids, $request->enabledProperties);
            } else {
                foreach ($existing_ids as $value) {
                    if($value < 100) array_push($ids, $value);
                }
                $ids = array_merge($ids, $request->enabledProperties);
            }

            $row->update(
                [
                    'property_ids' => json_encode($ids)
                ]
            );
        } else {
            DB::table('enabled_properties')->insert(
                [
                    'machine_id' => $id,
                    'user_id' => $user->id,
                    'property_ids' => json_encode($request->enabledProperties)
                ]
            );
        }

        return response()->json('Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
