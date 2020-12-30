<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Device;
use App\Company;
use App\Machine;
use App\Zone;
use App\Location;
use App\DeviceData;
use App\EnergyConsumption;
use App\Utilization;
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

	public function getDevices($pageNumber = 1) {
        $devices = Device::orderBy('sim_status')->orderBy('id')->paginate(config('settings.num_per_page'), ['*'], 'page', $pageNumber);
        $companies = Company::select('id', 'name')->get();

        foreach ($devices as $key => $device) {
            if(!$device->public_ip_sim) {
                try {
                    $device->public_ip_sim = $this->publicIP($device->iccid)->public_ip_sim;
                }
                catch( \Exception $e ) {
                }
            }
            if(!$device->sim_status) {

                try {
                    $device->sim_status = $this->querySIM($device->iccid)->sim_status;
                } catch( \Exception $e ) {

                }
            }
            if(!$device->carrier) {

                try {
                    $device->carrier = $this->carrierFromKoreAPI($device->iccid)->carrier;
                } catch( \Exception $e ) {

                }
            }
        }

        return response()->json([
            'devices' => $devices->items(),
            'companies' => $companies,
            'last_page' => $devices->lastPage()
        ]);
	}

    public function getAllDevices() {
        $devices = Device::orderBy('sim_status', 'ASC')->where('iccid', '<>', 0)->whereNotNull('iccid')->select('name', 'id', 'customer_assigned_name', 'tcu_added')->get();

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
            	if ($existing_devices->where('serial_number', $device->serial)->count() > 0) {
            		$numDuplicates++;
            		continue;
            	}
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
        }

        $device->zone_id = $request->zone_id;
        $device->customer_assigned_name = $request->customer_assigned_name;

        $device->save();

        return response()->json('Successfully assigned.');
    }

    public function updateRegistered(Request $request) {
        $device = Device::findOrFail($request->device_id);

        $configuration = $device->configuration;

        if(!$configuration) {
            return response()->json([
                'message' => 'Configuration Not Assinged'
            ], 422);
        }

        $configuration = json_decode($configuration->full_json);

        if(!$device->tcu_added) {
            if($request->register) {
                // Generate hash
                $config_hash = bin2hex(random_bytes(10));

                // Save hash in devices table
                $device->hash1 = $config_hash;
                $device->save();

                $configuration->config_hash = $config_hash;
            } else {
                $configuration->config_hash = $device->hash1;

                // if the request is revoke, plc tags should be empty
                $configuration->plctags = [];
            }

            // assign updated plc ip
            $configuration->plc_ip = $device->plc_ip;

            $req = [
                "targetDevice" => $device->serial_number,
                "requestJson" => $configuration
            ];
        } else {
            $tcu_configuration = json_decode(Machine::findOrFail(MACHINE_TRUETEMP_TCU)->full_json);

            if($request->register) {
                // Generate hash
                $config_hash1 = bin2hex(random_bytes(10));
                $config_hash2 = bin2hex(random_bytes(10));

                // Save hash in devices table
                $device->hash1 = $config_hash1;
                $device->hash2 = $config_hash2;
                $device->save();

                $configuration->config_hash = $config_hash1;
                $tcu_configuration->config_hash = $config_hash2;
            } else {
                $configuration->config_hash = $device->hash1;
                $tcu_configuration->config_hash = $device->hash2;

                // if the request is revoke, plc tags should be empty for both
                $configuration->plctags = [];
                $tcu_configuration->plctags = [];
            }

            // assign updated plc ip
            $configuration->plc_ip = $device->plc_ip;
            $tcu_configuration->plc_ip = $device->plc_ip;

            $device0 = new stdClass();
            $device1 = new stdClass();

            $device0->device_id = 0;
            $device0->config = $configuration;

            $device1->device_id = 1;
            $device1->config = $tcu_configuration;

            $multi_configuration = new stdClass();
            $multi_configuration->cmd = "multi_config";
            $multi_configuration->devices = [ $device0, $device1 ];

            $req = [
                "targetDevice" => $device->serial_number,
                "requestJson" => $multi_configuration
            ];
        }

        $client = new Client();

        try {
            $response = $client->post(
                config('app.acs_middleware_url'),
                [
                    'json' => $req
                ]
            );

            $device->registered = $request->register;
            $device->save();
            return response()->json('Successfully updated.');
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
                do {
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
                } while (count($data) === 0);

                return response()->json($data);
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
                do {
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
                } while (count($data) === 0);

                return response()->json($data);
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
    public function getDevicesAnalytics(Request $request, $location_id = 0) {
        $user = $request->user('api');
        
        $devices = $user->getMyDevices($location_id);

        foreach ($devices as $key => $device) {
            $device->status = $device->isRunning();
        }

        return response()->json([
            'devices' => $devices
        ]);
    }

    public function getTotalValues($array) {
        $result = 0;
        foreach($array as $item) {
			$value = json_decode($item->values);
			foreach($value as $num) {
				$result += $num;
			}
        }

        return $result;
    }

    public function getCapacityUtilizationFromDeviceId($device_id) {
        $utilizations = Utilization::where('device_id', (int)$device_id)->get();
        $result = $this->getTotalValues($utilizations);
        
        return $result;
    }

    public function getEnergyConsumptionFromDeviceId($device_id) {
        $consumptions = EnergyConsumption::where('device_id', (int)$device_id)->get();
        $result = $this->getTotalValues($consumptions);
        
        return $result;
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