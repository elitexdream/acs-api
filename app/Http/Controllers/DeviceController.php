<?php

namespace App\Http\Controllers;

use App\Running;
use App\Setting;
use Illuminate\Http\Request;
use App\Device;
use App\Company;
use App\Machine;
use App\SavedMachine;
use App\Zone;
use App\Location;
use App\DeviceData;
use App\EnergyConsumption;
use App\Utilization;
use App\TeltonikaConfiguration;
use App\Tag;
use App\EnabledProperty;
use App\Downtime;
use App\Imports\DevicesImport;
use Maatwebsite\Excel\Facades\Excel;
use GuzzleHttp\Client;
use Validator;
use DB;
use \stdClass;

class DeviceController extends Controller
{
    /*
    SIM status:
        1: Not initialized
        2: Active
        1: Suspended
        4: Scrapped
    */
    private $suspendURL = "https://prismproapi.sandbox.koretelematics.com/4/TransactionalAPI.svc/json/suspendDevice";
    private $activateURL = "https://prismproapi.sandbox.koretelematics.com/4/TransactionalAPI.svc/json/activateDevice";
    private $queryURL = "https://prismproapi.sandbox.koretelematics.com/4/TransactionalAPI.svc/json/queryDevice";

    private $teltonika_import_url = "https://rms.teltonika-networks.com/api/devices?limit=100";
    private $bearer_token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJqdGkiOiI2MjQxODgwMjFiMWIwY2UwNTA5ZDE3OWUzY2IxMDgxOGM2YmUzMjlhNjY3NTMwOGU0ZGI4NTEwODU4OThlZGUzNjY0NDQwODA1MDkwZWJjNSIsImlzcyI6Imh0dHBzOlwvXC9ybXMudGVsdG9uaWthLW5ldHdvcmtzLmNvbVwvYWNjb3VudCIsImlhdCI6MTYwNTY2NzMyNywibmJmIjoxNjA1NjY3MzI3LCJleHAiOjE2MzcyMDMzMjcsInN1YiI6IjI3OTcwIiwiY2xpZW50X2lkIjoiOTEyM2VhNjYtMmYxZC00MzljLWIxYzItMzExYWMwMTBhYWFkIiwiZmlyc3RfcGFydHkiOmZhbHNlfQ.I0kEBbsYDzIsBr3KFY9utxhSuKLM0zRgrPUBcUUNrIU3V58tce3LUgfV6r8yip5_pOe3ybVQdEoyIXNuehPUDIa8ZxJYadGw15cs9PLDyvM00ipAggnCgi0QinxUcb_5QjaMqfemhTlil9Zquly-P9tGy8GuT-QKAxMMCwGgou_LA3JH-5c7hoImbINMMyWQaHIrK3IiSVXyb0k_tP2tczy7TIjM5NFdzTMZXlVYEwTRZJ7U-_Vyb0ZnyyTJ_Y6_6CNp79vtQ8kVD_Xs_MVCQ0vQbO9qPRAxNu8noq7ZVo1eRdc1Q411puyzm3MeVSg1bWqqG4QboGiMYTyYclwhqA";

    public function getPlcStatus($deviceId) {
		$getLink = 'https://rms.teltonika-networks.com/api/devices/' . $deviceId;

		$client = new Client();

		try {
			$response = $client->get(
				$getLink,
				[
					'headers' => [
						'Authorization' => "Bearer " . $this->bearer_token,
						'Accept' => "application/json"
					]
				]
			);

			return json_decode($response->getBody())->data;
		} catch (\GuzzleHttp\Exception\BadResponseException $e) {
            return response()->json(json_decode($e->getResponse()->getBody()->getContents(), true), $e->getCode());
        }
	}

    public function isPlcRunning($machineId, $serialNumber) {
		$tag = Tag::where('configuration_id', $machineId)
            ->where('tag_name', Tag::NAMES['RUNNING'])
            ->first();

        if ($tag) {
            $running = Running::where('serial_number', $serialNumber)
                ->where('tag_id', $tag->tag_id)
                ->latest('timestamp')
                ->first();

            if ($running) {
                return json_decode($running->values)[0];
            }
        }

        return false;
    }

	public function getACSDevices(Request $request) {

        $devices_paginated = Device::orderBy('sim_status')
                ->orderBy('id')
                ->whereVisibleOnly()
                ->whereSimActive($request->filterForm['filters'])
                ->wherePlcLink($request->filterForm['filters'])
                ->whereRegistered($request->filterForm['filters'])
                ->whereSearchQuery($request->filterForm['searchQuery'] ?? '')
                ->with('checkin')
                ->paginate(config('settings.num_per_page'));


        $devices_paginated_array = $devices_paginated->toArray();

        $devices = collect($devices_paginated_array['data'])
            ->each(function ($device, $key) {
                try {

                    if (!isset($device['public_ip_sim']) || !$device['public_ip_sim']) {
                        $device['public_ip_sim'] = $this->publicIP($device['iccid'])->public_ip_sim ?? null;
                    }

                    if (!isset($device['sim_status']) || !$device['sim_status']) {
                        $device['sim_status'] = $this->querySIM($device['iccid'])->sim_status ?? null;
                    }

                    if (!isset($device['carrier']) || !$device['carrier']) {
                        $device->carrier = $this->carrierFromKoreAPI($device['iccid'])->carrier ?? null;
                    }

                } catch (\Exception $e) {

                }
            });

        return response()->json([
            'is_visible_only' => (new Setting())->getTypeVisibleValue() == 'configured',
            'hidden_devices' => Device::count() - $devices->count(),
            'devices' => $devices,
            'companies' => Company::select('id', 'name')->get(),
            'last_page' => $devices_paginated_array['last_page'],
            'first_page_url' => $devices_paginated_array['first_page_url'],
            'from' => $devices_paginated_array['from'],
            'last_page_url' => $devices_paginated_array['last_page_url'],
            'next_page_url' => $devices_paginated_array['next_page_url'],
            'path' => $devices_paginated_array['path'],
            'per_page' => $devices_paginated_array['per_page'],
            'prev_page_url' => $devices_paginated_array['prev_page_url'],
            'to' => $devices_paginated_array['to'],
            'total' => $devices_paginated_array['total'],
        ]);
	}

    public function getDeviceConfiguration(Request $request, $id) {
        $user = $request->user('api');

        $teltonika_configuration = TeltonikaConfiguration::where('teltonika_id', $id)->first();

        if(!$teltonika_configuration) {
            return response()->json([
                'status' => 'device_not_connected',
                'message' => 'Device not connected yet'], 404);
        }

        $configuration = new stdClass();

        $configuration->isTcuConnected = $teltonika_configuration->isTcuConnected();
        $configuration->plcMachineId = $teltonika_configuration->plcMachine()->id;
        $configuration->plcMachineName = $teltonika_configuration->plcMachine()->name;
        $configuration->plcSerialNumber = $teltonika_configuration->plcSerialNumber();
        $configuration->tcuMachineName = 'TrueTemp TCU';
        $configuration->isTcuConnected = $teltonika_configuration->isTcuConnected();
        $configuration->tcuSerialNumber = $teltonika_configuration->tcuSerialNumber();
        $configuration->plcAnalyticsGraphs = $teltonika_configuration->plcAnalyticsGraphs();
        $configuration->plcPropertiesGraphs = $teltonika_configuration->plcPropertiesGraphs();
        $configuration->plcEnabledAnalyticsGraphs = $teltonika_configuration->plcEnabledAnalyticsGraphs($user->id, $teltonika_configuration->plc_serial_number);
        $configuration->plcEnabledPropertiesGraphs = $teltonika_configuration->plcEnabledPropertiesGraphs($user->id, $teltonika_configuration->plc_serial_number);
        $configuration->tcuAnalyticsGraphs = $teltonika_configuration->tcuAnalyticsGraphs();
        $configuration->tcuPropertiesGraphs = $teltonika_configuration->tcuPropertiesGraphs();
        $configuration->tcuEnabledAnalyticsGraphs = $teltonika_configuration->tcuEnabledAnalyticsGraphs($user->id, $teltonika_configuration->tcu_serial_number);
        $configuration->tcuEnabledPropertiesGraphs = $teltonika_configuration->tcuEnabledPropertiesGraphs($user->id, $teltonika_configuration->tcu_serial_number);

        return response()->json(compact('configuration'));
    }

    public function updateEnabledProperties(Request $request)
    {
        $user = $request->user('api');

        $obj = EnabledProperty::firstOrCreate([
            'serial_number' => $request->serial_number,
            'user_id' => $user->id,
        ], [
            'property_ids' => json_encode($request->enabled_properties)
        ]);

        $ids = [];
        $existing_ids = json_decode($obj->property_ids);

        if ($request->isImportant) {
            foreach ($existing_ids as $value) {
                if ($value > 100) array_push($ids, $value);
            }

        } else {
            foreach ($existing_ids as $value) {
                if ($value < 100) array_push($ids, $value);
            }
        }

        EnabledProperty::whereId($obj->id)->update([
            'property_ids' => json_encode(array_merge($ids, $request->enabled_properties))
        ]);

        return response()->json('Updated successfully');
    }

    public function toggleActiveDevices() {
        $is_all_devices_visible_object = DB::table('settings')->where('type', 'is_all_devices_visible');

        if($is_all_devices_visible_object->first()->value == 'configured')
            $is_all_devices_visible_object->update([
                'value' => 'all'
            ]);
        else {
            $is_all_devices_visible_object->update([
                'value' => 'configured'
            ]);
        }

        return response()->json([
            'message' => 'Successfully updated'
        ]);
    }

    public function getAllDevices()
    {
        $devices = Device::orderBy('sim_status', 'ASC')
            ->where('iccid', '<>', 0)
            ->whereNotNull('iccid')
            ->select('name', 'id', 'customer_assigned_name', 'tcu_added')
            ->get();

        return response()->json(compact('devices'));
    }

    public function importDevices(Request $request) {
    	$existing_devices = Device::all();
    	$numAdded = 0;
    	$numDuplicates = 0;

        $client = new Client();
        try {
            $response = $client->get(
                $this->teltonika_import_url,
                [
                    'headers' => [
                        'Authorization' => "Bearer " . $this->bearer_token
                    ]
                ]
            );

        	$devices = json_decode($response->getBody())->data;
            foreach ($devices as $key => $device) {
                $exisitng_device = $existing_devices->where('serial_number', $device->serial)->first();
            	if ($exisitng_device) {
                    $exisitng_device->update([
                        'name' => $device->name,
                        'lan_mac_address' => $device->mac,
                    ]);
            		$numDuplicates++;
            		continue;
            	} else {
                	Device::create([
        	           'device_id' => $device->id,
                       'name' => $device->name,
                       'customer_assigned_name' => $device->name,
                       'serial_number' => $device->serial,
        	           'imei' => $device->imei,
        	           'lan_mac_address' => $device->mac,
                       'iccid' => substr($device->iccid, 0, -1),
                       'public_ip_sim' => null,
                       'machine_id' => null,
                       'company_id' => null,
                       'registered' => false
                	]);
                	$numAdded++;
                }
            }
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            return response()->json(json_decode($e->getResponse()->getBody()->getContents(), true), $e->getCode());
        }


        return response()->json([
    		'numAdded' => $numAdded,
    		'numDuplicates' => $numDuplicates
        ]);
    }

    public function deviceAssigned(Request $request) {

        $validator = Validator::make($request->all(), [
            'plc_ip' => 'required'
        ]);

        if ($validator->fails())
        {
            return response()->json(['error'=>$validator->errors()], 422);
        }

        $device = Device::findOrFail($request->device_id);

        $device->company_id = $request->company_id;
        $device->machine_id = $request->machine_id;
        $device->tcu_added = $request->tcu_added;
        $device->plc_ip = $request->plc_ip;
        $device->name = $request->device_name;

        $device->save();

        return response()->json('Successfully assigned.');
    }

    /*
        Assign zone to a device and update machine name in machine mapping page
    */
    public function updateCustomerDevice(Request $request) {

        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'customer_assigned_name' => 'required',
        ]);

        if ($validator->fails())
        {
            return response()->json(['error'=>$validator->errors()], 422);
        }

        $device = Device::findOrFail($request->id);

        if($request->zone_id) {
            $location = Zone::findOrFail($request->zone_id)->location;
            $device->location_id = $location->id;
        } else {
            $device->location_id = 0;
        }

        $device->zone_id = $request->zone_id;

        $device->customer_assigned_name = $request->customer_assigned_name;

        $device->save();

        return response()->json('Successfully assigned.');
    }

    public function sendDeviceConfiguration(Request $request) {
        $device = Device::findOrFail($request->device_id);

        $device_configuration = new stdClass();
        $device_configuration_plc = new stdClass();
        $device_configuration_tcu = new stdClass();

        $device_configuration_plc->ip = $request->device_configuration_form['plc_ip'];
        $device_configuration_plc->modbus_tcp_port = $request->device_configuration_form['plc_modbus_tcp_port'];
        $device_configuration_plc->serial_num = $request->device_configuration_form['plc_serial_number'];

        $device_configuration_tcu->serial_num = $request->device_configuration_form['tcu_serial_number'];
        $device_configuration_tcu->port = $request->device_configuration_form['tcu_port'];
        $device_configuration_tcu->base_addr = $request->device_configuration_form['tcu_base_addr'];
        $device_configuration_tcu->baud = $request->device_configuration_form['tcu_baud'];
        $device_configuration_tcu->parity = $request->device_configuration_form['tcu_parity'];
        $device_configuration_tcu->data_bits = $request->device_configuration_form['tcu_data_bits'];
        $device_configuration_tcu->stop_bits = $request->device_configuration_form['tcu_stop_bits'];
        $device_configuration_tcu->byte_timeout = $request->device_configuration_form['tcu_bype_timeout'];
        $device_configuration_tcu->resp_timeout = $request->device_configuration_form['tcu_resp_timeout'];

        $device_configuration->batch_size = 4000;
        $device_configuration->batch_timeout = 60;
        $device_configuration->cmd = 'daemon_config';

        if(!$request->device_configuration_form['tcuAdded'])
            $device_configuration_tcu->port = '';

        $device_configuration->plc = $device_configuration_plc;
        $device_configuration->true_temp = $device_configuration_tcu;

        $req = [
            "targetDevice" => $device->serial_number,
            "requestJson" => $device_configuration
        ];

        $client = new Client();

        try {
            $response = $client->post(
                config('app.acs_middleware_url'),
                [
                    'json' => $req
                ]
            );

            return response()->json('Configuration successfully sent.');
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            return response()->json(json_decode($e->getResponse()->getBody()->getContents(), true), $e->getCode());
        }
    }

    public function suspendSIM($iccid) {
        $device = Device::where('iccid', $iccid)->first();

        if(!$device) {
            return response()->json('Device Not Found', 404);
        }

        $client = new Client();
        try {
            $response = $client->post(
                $this->suspendURL,
                [
                    'headers' => ['Content-type' => 'application/json'],
                    'auth' => [
                        'ACSGroup_API',
                        'HBSMYJM2'
                    ],
                    'json' => [
                        "deviceNumber" => $device->iccid,
                    ]
                ]
            );

            return $response->getBody();
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            return response()->json(json_decode($e->getResponse()->getBody()->getContents(), true), $e->getCode());
        }
    }

    public function remoteWeb($deviceid) {
        $device = Device::where('device_id', $deviceid)->first();
        if(!$device) {
            return response()->json('Device Not Found', 404);
        }
        $postControl = 'https://rms.teltonika-networks.com/api/devices/' . $deviceid . '/connect/webui';
        $getLink = 'https://rms.teltonika-networks.com/api/devices/' . $deviceid . '/links';

        $client = new Client();

        try {
            while(1) {
                $res = $client->post(
                    $postControl,
                    [
                        'headers' => [
                            'Authorization' => "Bearer " . $this->bearer_token
                        ],
                        'json' => [
                            "duration" => 400
                        ]
                    ]
                );


                if ($res) {
                    $response = $client->get(
                        $getLink,
                        [
                            'headers' => [
                                'Authorization' => "Bearer " . $this->bearer_token
                            ],
                            'json' => [
                                "type" => "webui"
                            ],
                        ]
                    );
                    $data = json_decode($response->getBody()->getContents())->data;

                    if(count($data))
                        return response()->json($data);
                }
            }
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            return response()->json(json_decode($e->getResponse()->getBody()->getContents(), true), $e->getCode());
        }
    }

    public function remoteCli($deviceid) {
        $device = Device::where('device_id', $deviceid)->first();
        if(!$device) {
            return response()->json('Device Not Found', 404);
        }
        $postControl = 'https://rms.teltonika-networks.com/api/devices/' . $deviceid . '/connect/cli';
        $getLink = 'https://rms.teltonika-networks.com/api/devices/' . $deviceid . '/links';

        $client = new Client();

        try {
            while(1) {
                $res = $client->post(
                    $postControl,
                    [
                        'headers' => [
                            'Authorization' => "Bearer " . $this->bearer_token
                        ],
                        'json' => [
                            "duration" => 400
                        ],
                    ]
                );
                if ($res) {
                    $response = $client->get(
                        $getLink,
                        [
                            'headers' => [
                                'Authorization' => "Bearer " . $this->bearer_token
                            ],
                            'json' => [
                                "type" => "cli"
                            ],
                        ]
                    );

                    $data = json_decode($response->getBody()->getContents())->data;

                    if(count($data))
                        return response()->json($data);
                }
            }
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            return response()->json(json_decode($e->getResponse()->getBody()->getContents(), true), $e->getCode());
        }
    }

    public function querySIM($iccid) {
        if(!$iccid) {
            return response()->json('Invalid ICCID', 404);
        }

        $device = Device::where('iccid', $iccid)->first();

        if(!$device) {
            return response()->json('Device Not Found', 404);
        }

        $client = new Client();
        try {
            $response = $client->post(
                $this->queryURL,
                [
                    'headers' => ['Content-type' => 'application/json'],
                    'auth' => [
                        'ACSGroup_API',
                        'HBSMYJM2'
                    ],
                    'json' => [
                        "deviceNumber" => $iccid,
                    ],

                ]
            );

            $device->sim_status = json_decode($response->getBody())->d->status;
            $device->save();

            return $device;
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            return response()->json(json_decode($e->getResponse()->getBody()->getContents(), true), $e->getCode());
        }
    }

    public function publicIP($iccid) {
        if(!$iccid) {
            return response()->json('Invalid ICCID', 404);
        }

        $device = Device::where('iccid', $iccid)->first();

        if(!$device) {
            return response()->json('Device Not Found', 404);
        }

        $client = new Client();
        try {
            $response = $client->post(
                $this->queryURL,
                [
                    'headers' => ['Content-type' => 'application/json'],
                    'auth' => [
                        'ACSGroup_API',
                        'HBSMYJM2'
                    ],
                    'json' => [
                        "deviceNumber" => $iccid,
                    ],
                ]
            );

            $device->public_ip_sim = json_decode($response->getBody())->d->staticIP;
            $device->save();

            return $device;
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            return response()->json(json_decode($e->getResponse()->getBody()->getContents(), true), $e->getCode());
        }
    }

    public function carrierFromKoreAPI($iccid) {
        if(!$iccid) {
            return response()->json('Invalid ICCID', 404);
        }

        $device = Device::where('iccid', $iccid)->first();

        if(!$device) {
            return response()->json('Device Not Found', 404);
        }

        $client = new Client();
        try {
            $response = $client->post(
                $this->queryURL,
                [
                    'headers' => ['Content-type' => 'application/json'],
                    'auth' => [
                        'ACSGroup_API',
                        'HBSMYJM2'
                    ],
                    'json' => [
                        "deviceNumber" => $iccid,
                    ],
                ]
            );
            $features = json_decode($response->getBody())->d->lstFeatures;
            foreach ($features as $key => $feature) {
                if (strpos($feature, 'FEAT015100') !== false) {
                    $feature = str_replace("FEAT015100: ", "", $feature);

                    if(strpos($feature, 'KTUSA') !== false) {
                        $device->carrier = str_replace("KTUSA", "T-Mobile", $feature);
                    } else if(strpos($feature, 'KUSG') !== false) {
                        $device->carrier = str_replace("KUSG", "AT&T", $feature);
                    } else if(strpos($feature, 'VZWLTE') !== false) {
                        $device->carrier = str_replace("VZWLTE", "Verizon", $feature);
                    }
                    break;
                }
            }

            $device->save();

            return $device;
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            return response()->json(json_decode($e->getResponse()->getBody()->getContents(), true), $e->getCode());
        }
    }

    public function getCustomerDevices(Request $request) {
        $user = $request->user('api');
        $devices = $user->company->devices;

        return response()->json([
            'devices' => $devices
        ]);
    }

    /*
        Get devices with analytics
    */
    public function getDevicesAnalytics(Request $request) {
        $user = $request->user('api');
        $location = $request->location_id;
        $page = $request->page;
        $itemsPerPage = $request->itemsPerPage;

        $query = null;

        if ($request->company_id == 0) {
            if($user->hasRole(['acs_admin', 'acs_manager', 'acs_viewer'])) {
                if($location) {
                    $query = Device::where('location_id', $location)->orderBy('sim_status')->orderBy('id');
                }
                else
                    $query = Device::orderBy('sim_status')->orderBy('id');
            } else {
                if($location) {
                    $query = $user->company->devices()->where('location_id', $location)->orderBy('sim_status')->orderBy('id');
                }
                else
                    $query = $user->company->devices()->orderBy('sim_status')->orderBy('id');
            }
        } else {
            $company = Company::where('id', $request->company_id)->first();

            if ($location) {
                $query = $company->devices()->where('location_id', $location)->orderBy('sim_status')->orderBy('id');
            } else {
                $query = $company->devices()->orderBy('sim_status')->orderBy('id');
            }
        }

        $query->with(['teltonikaConfiguration', 'configuration:id,name']);
        $devices = $query->paginate($itemsPerPage, ['*'], 'page', $page);
        foreach ($devices as $key => $device) {
            if ($device->teltonikaConfiguration && $device->teltonikaConfiguration->plc_serial_number) {
                $running = $this->isPlcRunning($device->machine_id, $device->teltonikaConfiguration->plc_serial_number);
            } else {
                $running = false;
            }

            if ($device->teltonikaConfiguration && $device->teltonikaConfiguration->plc_status) {
                $plcLinkStatus = true;
            } else {
                $plcLinkStatus = false;
            }

            // $plcStatus = $this->getPlcStatus($device->device_id);

            // if (!isset($plcStatus->status)) {
            //     $device->status = 'routerNotConnected';
            // } else {
            //     if($plcStatus->status != 1) {
            //         $device->status = 'routerNotConnected';
            //     } else if (!$plcLinkStatus) {
            //         $device->status = 'plcNotConnected';
            //     } else if ($running) {
            //         $device->status = 'running';
            //     } else {
            //         $device->status = 'shutOff';
            //     }
            // }

            if (!$plcLinkStatus) {
                $device->status = 'plcNotConnected';
            } else if ($running) {
                $device->status = 'running';
            } else {
                $device->status = 'shutOff';
            }
        }

        return response()->json(compact('devices'));
    }

    public function getSavedMachines(Request $request) {
        $user = $request->user('api');
        $page = $request->page;
        $itemsPerPage = $request->itemsPerPage;

        $query = null;

        $query = Device::join('saved_machines', 'saved_machines.device_id', '=', 'devices.id')
                        ->where('saved_machines.user_id', $user->id)
                        ->select('devices.*')->orderBy('sim_status')->orderBy('id');
        $query->with(['teltonikaConfiguration', 'configuration:id,name']);
        $devices = $query->paginate($itemsPerPage, ['*'], 'page', $page);

        foreach ($devices as $key => $device) {
            if ($device->teltonikaConfiguration && $device->teltonikaConfiguration->plc_serial_number) {
                $running = $this->isPlcRunning($device->machine_id, $device->teltonikaConfiguration->plc_serial_number);
            } else {
                $running = false;
            }

            if ($device->teltonikaConfiguration && $device->teltonikaConfiguration->plc_status) {
                $plcLinkStatus = true;
            } else {
                $plcLinkStatus = false;
            }

            $plcStatus = $this->getPlcStatus($device->device_id);

            if (!isset($plcStatus->status)) {
                $device->status = 'routerNotConnected';
            } else {
                if($plcStatus->status != 1) {
                    $device->status = 'routerNotConnected';
                } else if (!$plcLinkStatus) {
                    $device->status = 'plcNotConnected';
                } else if ($running) {
                    $device->status = 'running';
                } else {
                    $device->status = 'shutOff';
                }
            }
        }

        return response()->json(compact('devices'));
    }

    public function getDashboardMachinesTable(Request $request) {
        $user = $request->user('api');

        $location = $request->location;
        $zone = $request->zone;
        $page = $request->page;

        if($user->hasRole(['acs_admin', 'acs_manager', 'acs_viewer'])) {
            $query = Device::where('location_id', $location)->where('zone_id', $zone);
        } else {
            $query = $user->company->devices()->where('location_id', $location)->where('zone_id', $zone);
        }

        $query->with('teltonikaConfiguration', 'configuration:id,name');
        $devices = $query->paginate($request->itemsPerPage, ['*'], 'page', $page);

        foreach ($devices as $key => $device) {
            $downtime_distribution = [0, 0, 0];

            if ($device->teltonikaConfiguration && $device->teltonikaConfiguration->plc_serial_number) {
                $running = $this->isPlcRunning($device->machine_id, $device->teltonikaConfiguration->plc_serial_number);
            } else {
                $running = false;
            }

            if ($device->teltonikaConfiguration && $device->teltonikaConfiguration->plc_status) {
                $plcLinkStatus = true;
            } else {
                $plcLinkStatus = false;
            }

            $plcStatus = $this->getPlcStatus($device->device_id);

            if (!isset($plcStatus->status)) {
                $device->status = 'routerNotConnected';
            } else {
                if($plcStatus->status != 1) {
                    $device->status = 'routerNotConnected';
                } else if (!$plcLinkStatus) {
                    $device->status = 'plcNotConnected';
                } else if ($running) {
                    $device->status = 'running';
                } else {
                    $device->status = 'shutOff';
                }
            }

            $device->utilization = '32%';
            $device->color = 'green';
            $device->value = 75;
            $device->oee = '93.1%';
            $device->performance = '78%';
            $device->rate = 56;
            $device->downtimeDistribution = $downtime_distribution;

        }

        return response()->json(compact('devices'));
    }

    public function getDowntimeGraphData(Request $request) {
        $timeFrom = $request->from / 1000;
        $timeTo = $request->to / 1000;

        $dates = [];

        $query = "select
                sq2.reason_name as name,
                json_agg(ROUND(sq2.sum::numeric, 3) order by sq2.generated_date_int) as data
            from (
                select
                    sq.reason_name,
                    sq.generated_date_int,
                    coalesce(sum(corrected_end_dt_int - corrected_start_dt_int)/(60*60), 0) as sum
                from (
                    with
                    datetime_filter as (
                        select " .
                            $timeFrom . " as start_dt," .
                            $timeTo . " as end_dt
                    ),
                    generated_date as (
                        select generate_series(
                            date_trunc('day', to_timestamp(start_dt)),
                            date_trunc('day', to_timestamp(end_dt)),
                            interval '1 day'
                        ) as generated_date
                        from datetime_filter
                    ),
                    generated_date_int as (
                        select
                            extract(epoch from generated_date) as dt
                        from generated_date
                    )
                    select       
                        generated_date_int.dt as generated_date_int,
                        r.name as reason_name,
                        case when d.start_time is not null then
                            case when d.start_time < generated_date_int.dt then greatest(generated_date_int.dt, datetime_filter.start_dt) else greatest(d.start_time, datetime_filter.start_dt) end
                        else null end as corrected_start_dt_int,
                    
                        case when d.end_time is not null then
                            case when d.end_time > generated_date_int.dt + 60*60*24 
                            then 
                                case when datetime_filter.end_dt = generated_date_int.dt
                                    then generated_date_int.dt + 60*60*24
                                    else least(datetime_filter.end_dt, generated_date_int.dt + 60*60*24)
                                end
                            else least(d.end_time, generated_date_int.dt + 60*60*24)
                            end
                        else null 
                        end as corrected_end_dt_int
                    from datetime_filter
                    left join generated_date_int on 0 = 0
                    left join downtime_reasons r on 0 = 0
                    left join downtimes d on d.reason_id = r.id
                        and d.start_time < case when generated_date_int.dt = datetime_filter.end_dt then generated_date_int.dt + 60*60*24 else least(generated_date_int.dt + 60*60*24, datetime_filter.end_dt) end
                        and d.end_time > greatest(generated_date_int.dt, datetime_filter.start_dt)
                    order by generated_date_int.dt, r.name, d.start_time
                ) as sq
                group by sq.reason_name, sq.generated_date_int
            ) as sq2
            group by sq2.reason_name";

        $date_generate_query = "select generate_series(
            date_trunc('day', to_timestamp(".$timeFrom.")),
            date_trunc('day', to_timestamp(".$timeTo.")),
            interval '1 day'
            )::date as generated_date";

        $series = DB::select($query);
        $generated_dates = DB::select($date_generate_query);

        foreach ($series as $data) {
            $data->data = json_decode($data->data);
        };

        foreach ($generated_dates as $date) {
            array_push($dates, $date->generated_date);
        };

        return response()->json(compact('series', 'dates'));
    }

    public function getDowntimeByTypeGraphData(Request $request) {
        $timeFrom = $request->from / 1000;
        $timeTo = $request->to / 1000;

        $query = "select
                sq2.type_name as name,
                ROUND(sum(sum)::numeric, 3) as data
            from (
                select
                    sq.type_name,
                    sq.generated_date_int,
                    coalesce(sum(corrected_end_dt_int - corrected_start_dt_int)/(60*60), 0) as sum
                from (
                    with
                    datetime_filter as (
                        select " .
                            $timeFrom . " as start_dt," .
                            $timeTo . " as end_dt
                    ),
                    generated_date as (
                        select generate_series(
                            date_trunc('day', to_timestamp(start_dt)),
                            date_trunc('day', to_timestamp(end_dt)),
                            interval '1 day'
                        ) as generated_date
                        from datetime_filter
                    ),
                    generated_date_int as (
                        select
                            extract(epoch from generated_date) as dt
                        from generated_date
                    )
                    select       
                        generated_date_int.dt as generated_date_int,
                        t.name as type_name,
                        case when d.start_time is not null then
                            case when d.start_time < generated_date_int.dt then greatest(generated_date_int.dt, datetime_filter.start_dt) else greatest(d.start_time, datetime_filter.start_dt) end
                        else null end as corrected_start_dt_int,
                    
                        case when d.end_time is not null then
                            case when d.end_time > generated_date_int.dt + 60*60*24 
                            then 
                                case when datetime_filter.end_dt = generated_date_int.dt
                                    then generated_date_int.dt + 60*60*24
                                    else least(datetime_filter.end_dt, generated_date_int.dt + 60*60*24)
                                end
                            else least(d.end_time, generated_date_int.dt + 60*60*24)
                            end
                        else null 
                        end as corrected_end_dt_int
                    from datetime_filter
                    left join generated_date_int on 0 = 0
                    left join downtime_type t on 0 = 0
                    left join downtimes d on d.type = t.id
                        and d.start_time < case when generated_date_int.dt = datetime_filter.end_dt then generated_date_int.dt + 60*60*24 else least(generated_date_int.dt + 60*60*24, datetime_filter.end_dt) end
                        and d.end_time > greatest(generated_date_int.dt, datetime_filter.start_dt)
                    order by generated_date_int.dt, t.name, d.start_time
                ) as sq
                group by sq.type_name, sq.generated_date_int
            ) as sq2
            group by sq2.type_name";

        $series = DB::select($query);

        foreach ($series as $data) {
            $data->data = json_decode($data->data);
        };

        return response()->json(compact('series'));
    }

    public function getDowntimeByReasonGraphData(Request $request) {
        $timeFrom = $request->from / 1000;
        $timeTo = $request->to / 1000;

        $query = "select
                sq2.reason_name as name,
                ROUND(sum(sum)::numeric, 3) as data
            from (
                select
                    sq.reason_name,
                    sq.generated_date_int,
                    coalesce(sum(corrected_end_dt_int - corrected_start_dt_int)/(60*60), 0) as sum
                from (
                    with
                    datetime_filter as (
                        select " .
                            $timeFrom . " as start_dt, " .
                            $timeTo . " as end_dt
                    ),
                    generated_date as (
                        select generate_series(
                            date_trunc('day', to_timestamp(start_dt)),
                            date_trunc('day', to_timestamp(end_dt)),
                            interval '1 day'
                        ) as generated_date
                        from datetime_filter
                    ),
                    generated_date_int as (
                        select
                            extract(epoch from generated_date) as dt
                        from generated_date
                    )
                    select       
                        generated_date_int.dt as generated_date_int,
                        r.name as reason_name,
                        case when d.start_time is not null then
                            case when d.start_time < generated_date_int.dt then greatest(generated_date_int.dt, datetime_filter.start_dt) else greatest(d.start_time, datetime_filter.start_dt) end
                        else null end as corrected_start_dt_int,
                    
                        case when d.end_time is not null then
                            case when d.end_time > generated_date_int.dt + 60*60*24 
                            then 
                                case when datetime_filter.end_dt = generated_date_int.dt
                                    then generated_date_int.dt + 60*60*24
                                    else least(datetime_filter.end_dt, generated_date_int.dt + 60*60*24)
                                end
                            else least(d.end_time, generated_date_int.dt + 60*60*24)
                            end
                        else null
                        end as corrected_end_dt_int
                    from datetime_filter
                    left join generated_date_int on 0 = 0
                    left join downtime_reasons r on 0 = 0
                    left join downtimes d on d.reason_id = r.id
                        and d.start_time < case when generated_date_int.dt = datetime_filter.end_dt then generated_date_int.dt + 60*60*24 else least(generated_date_int.dt + 60*60*24, datetime_filter.end_dt) end
                        and d.end_time > greatest(generated_date_int.dt, datetime_filter.start_dt)
                    order by generated_date_int.dt, r.name, d.start_time
                ) as sq
                group by sq.reason_name, sq.generated_date_int
            ) as sq2
            group by sq2.reason_name";

        $series = DB::select($query);

        foreach ($series as $data) {
            $data->data = json_decode($data->data);
        };

        return response()->json(compact('series'));
    }

    public function testFunction(Request $request) {
        set_time_limit(0);

        $limit = $request->limit;

        $devices = DeviceData::where('created_at', '')->limit($limit)->get();

        foreach ($devices as $device) {
            $device->update([
                'created_at' => gmdate("D, d M Y H:i:s \G\M\T", $device->timestamp)
            ]);
        }

        dd($devices);
    }

    public function testAzureJson(Request $request) {
        $client = new Client();
        try {
            $response = $client->post(
                config('app.acs_middleware_url'),
                [
                    'json' => $request->all()
                ]
            );

            return $response->getBody();
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            return response()->json(json_decode($e->getResponse()->getBody()->getContents(), true), $e->getCode());
        }
    }
}
