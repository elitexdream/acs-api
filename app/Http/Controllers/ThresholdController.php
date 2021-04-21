<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Threshold;

use \stdClass;

class ThresholdController extends Controller
{
    public function addThreshold(Request $request) {
        $user = $request->user('api');
        $conditions = $request->conditions;

        foreach ($conditions as $key => $condition) {
            Threshold::create([
                'user_id' => $user->id,
                'device_id' => $request->deviceId,
                'tag_id' => $condition['telemetry'],
                'operator' => $condition['operator'],
                'value' => $condition['value'],
                'sms_info' => $request->smsForm,
                'email_info' => $request->emailForm
            ]);
        }

        return response()->json([
            'message' => 'Threshold added successfully'
        ]);
    }
}
