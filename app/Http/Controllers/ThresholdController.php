<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Threshold;
use App\MachineTag;
use App\AlarmType;
use App\Device;

use \stdClass;

class ThresholdController extends Controller
{
    public function addThreshold(Request $request) {
        $user = $request->user('api');
        $conditions = $request->conditions;
        $condition_name = [];

        foreach ($conditions as $key => $condition) {
            $option = Threshold::where('tag_id', $condition['telemetry'])
                                ->where('operator', $condition['operator'])
                                ->where('value', $condition['value'])
                                ->where('device_id', $request->deviceId)
                                ->where('user_id', $user->id)
                                ->first();

            if ($option) {
                return response()->json([
                    'message' => 'Condition with same operator already exists',
                    'status' => 'fail'
                ]);
            }

            Threshold::create([
                'user_id' => $user->id,
                'device_id' => $request->deviceId,
                'tag_id' => $condition['telemetry'],
                'operator' => $condition['operator'],
                'value' => $condition['value'],
                'sms_info' => json_encode($request->smsForm),
                'email_info' => json_encode($request->emailForm)
            ]);
        }

        return response()->json([
            'message' => 'Threshold added successfully',
            'status' => 'success'
        ]);
    }

    public function getThresholdList(Request $request) {
        $user = $request->user('api');
        if ($user->hasRole(['customer_admin'])) {
            $conditions = Threshold::all();
        } else {
            $conditions = Threshold::where('user_id', $user->id)->get();
        }

        foreach ($conditions as $key => $condition) {
            $machine_id = Device::where('device_id', $condition['device_id'])->first()->machine_id;

            $tag = MachineTag::where('configuration_id', $machine_id)->where('tag_id', $condition['tag_id'])->first();

            if (!$tag) {
                $tag = AlarmType::where('machine_id', $machine_id)->where('tag_id', $condition['tag_id'])->first();
            }

            $smsInfo = json_decode($condition['sms_info']);
            $emailInfo = json_decode($condition['email_info']);

            if ($smsInfo->name && $smsInfo->to && $smsInfo->note) {
                $condition['sms'] = $smsInfo->to;
            }
            
            if ($emailInfo->name && $emailInfo->to && $emailInfo->note) {
                $condition['email'] = $emailInfo->to;
            }

            $condition['option'] = $tag->name . " " . $this->getMathExpressionFromString($condition['operator']). " " . $condition['value'];
        }

        return response()->json(compact('conditions'));
    }

    public function changeStatus(Request $request, $id) {
        $threshold = Threshold::where('id', $id)->first();

        $threshold->update([
            'status' => !$threshold->status
        ]);
    }

	public function getMathExpressionFromString($string) {
		if ($string == 'Equals') {
			return '=';
		} else if ($string == 'Does not equal') {
			return '!=';
		} else if ($string == 'Is greater than') {
			return '>';
		} else if ($string == 'Is greater than or equal to') {
			return '>=';
		} else if ($string == 'Is less than') {
			return '<';
		} else if ($string == 'Is less than or equal to') {
			return '<=';
		} else if ($string == 'Is in') {
			return 'in';
		} else if ($string == 'Is not in') {
			return 'not in';
		} else return '';
	}
}
