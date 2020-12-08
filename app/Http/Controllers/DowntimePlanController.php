<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\downtimePlan;
use App\Machine;
use Validator;

class DowntimePlanController extends Controller
{
     public function index(Request $request) {
        $user = $request->user('api');

        $downtimePlans = $user->company->downtimePlans;

        $machine_ids = $user->company->devices->pluck('machine_id');
        $machines = Machine::whereIn('id', $machine_ids)->select('id', 'name')->get();

        return response()->json(compact('downtimePlans', 'machines'));
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
        	'machine' => 'required',
            'timeTo' => 'required',
            'timeFrom' => 'required',
            'dateTo' => 'required',
            'dateFrom' => 'required',
            'reason' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 422);            
        }

        $user = $request->user('api');

        downtimePlan::create([
            'company_id' => $user->company->id,
            'machine_id' => $request->machine,
            'reason' => $request->reason,
            'date_from' => $request->dateFrom,
            'date_to' => $request->dateTo,
            'time_from' => $request->timeFrom,
            'time_to' => $request->timeTo,
        ]);

        $downtimePlans = $user->company->downtimePlans;

        return response()->json('Created successfully');
    }

    public function update(Request $request, $id) {
        $plan = downtimePlan::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'machine' => 'required',
            'timeTo' => 'required',
            'timeFrom' => 'required',
            'dateTo' => 'required',
            'dateFrom' => 'required',
            'reason' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 422);            
        }

        $plan->machine_id = $request->machine;
        $plan->reason = $request->reason;
        $plan->date_from = $request->dateFrom;
        $plan->date_to = $request->dateTo;
        $plan->time_from = $request->timeFrom;
        $plan->time_to = $request->timeTo;

        $plan->save();

        return response()->json('Updated successfully');
    }
}
