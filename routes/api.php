<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Authentication api routes
Route::post('auth/check', 'UserController@check');
Route::post('auth/signin', 'UserController@login');
Route::post('auth/signup', 'UserController@register');
Route::post('auth/password-reset', 'UserController@passwordReset');

Route::middleware('auth')->group(function () {
    Route::get('/auth/logout', 'UserController@logout');
	Route::post('/auth/update-password', 'UserController@updatePassword');
	Route::get('/auth/user', 'UserController@getUser');
});

Route::middleware('auth')->group(function () {
	Route::post('/profile/timezone', 'UserController@updateTimezone');
	Route::get('/profile/timezones', 'UserController@getTimezones');
});

Route::group(['prefix' => 'locations'], function () {
	Route::get('/', 'LocationController@index')->middleware('auth:acs_admin,acs_manager,acs_viewer,customer_admin,customer_manager');
	Route::post('/add', 'LocationController@store')->middleware('auth:customer_admin,customer_manager');
	Route::patch('/update', 'LocationController@update')->middleware('auth:customer_admin,customer_manager');
});

Route::group(['prefix' => 'zones'], function () {
	Route::get('/', 'ZoneController@index')->middleware('auth:acs_admin,acs_manager,acs_viewer,customer_admin,customer_manager,customer_operator');
	Route::post('/add', 'ZoneController@store')->middleware('auth:customer_admin,customer_manager');
	Route::patch('/update', 'ZoneController@update')->middleware('auth:customer_admin,customer_manager');
});

Route::group(['prefix' => 'configurations'], function () {
	Route::get('/', 'ConfigurationController@getConfigurationNames');
	Route::get('/{id}', 'ConfigurationController@getConfiguration')->middleware('auth:super_admin');
	Route::post('/{id}', 'ConfigurationController@saveConfiguration')->middleware('auth:super_admin');
});

Route::group(['prefix' => 'devices'], function () {
	Route::get('/customer-devices', 'DeviceController@getCustomerDevices')->middleware('auth:customer_admin,customer_manager,customer_operator');
	Route::get('/all', 'DeviceController@getAllDevices')->middleware('auth:acs_admin,acs_manager,acs_viewer');
	Route::post('/devices-analytics', 'DeviceController@getDevicesAnalytics')->middleware('auth:customer_admin,customer_manager,customer_operator,acs_admin,acs_manager,acs_viewer');
	Route::post('/assign-zone', 'DeviceController@updateCustomerDevice')->middleware('auth:customer_admin,customer_manager,customer_operator');
});
Route::group(['prefix' => 'downtime-plans'], function () {
	Route::get('/', 'DowntimePlanController@index')->middleware('auth:customer_admin,customer_manager,customer_operator');
	Route::post('/store', 'DowntimePlanController@store')->middleware('auth:customer_admin,customer_manager,customer_operator');
	Route::post('/update/{id}', 'DowntimePlanController@update')->middleware('auth:customer_admin,customer_manager,customer_operator');
});

Route::group(['prefix' => 'company-users'], function () {
	Route::get('/', 'UserController@getCompanyUsers')->middleware('auth:customer_admin,customer_manager,customer_operator');
	Route::post('/store', 'UserController@addCompanyUser')->middleware('auth:customer_admin');
	Route::get('/init-create-account', 'UserController@initCreateAccount')->middleware('auth:customer_admin');
	Route::get('/init-edit-account/{id}', 'UserController@initEditAccount')->middleware('auth:customer_admin');
	Route::post('/update-account/{id}', 'UserController@updateCompanyUserAccount')->middleware('auth:customer_admin');
	Route::post('/update-information/{id}', 'UserController@updateCompanyUserInformation')->middleware('auth:customer_admin');
});

Route::group(['prefix' => 'acs-users'], function () {
	Route::get('/', 'UserController@initAcsUsers')->middleware('auth:acs_admin,acs_manager,acs_viewer');
	Route::get('/init-create', 'UserController@initCreateAcsUser')->middleware('auth:acs_admin');
	Route::get('/init-edit/{id}', 'UserController@initEditAcsUser')->middleware('auth:acs_admin');
	Route::post('/store', 'UserController@addAcsUser')->middleware('auth:acs_admin');
	Route::post('/update-account/{id}', 'UserController@updateAcsUserAccount')->middleware('auth:acs_admin');
	Route::post('/update-information/{id}', 'UserController@updateAcsUserInformation')->middleware('auth:acs_admin');
});

Route::group(['prefix' => 'app-settings'], function () {
	Route::post('/grab-colors', 'SwatchController@grabColors');
	Route::post('/get-setting', 'SettingController@getSetting');
	Route::post('/set-private-colors', 'SettingController@setPrivateColors');
	Route::post('/upload-logo', 'SettingController@uploadLogo');
	Route::post('/update-auth-background', 'SettingController@updateAuthBackground');
	Route::post('/reset', 'SettingController@resetSettings');
});

Route::group(['prefix' => 'dashboard'], function () {
	Route::get('/init-locations-table', 'MachineController@getLocationsTableData')->middleware('auth:acs_admin,acs_manager,acs_viewer,customer_admin,customer_manager,customer_operator');
	Route::get('/init-zones-table/{id}', 'MachineController@getZonesTableData')->middleware('auth:acs_admin,acs_manager,acs_viewer,customer_admin,customer_manager,customer_operator');
	Route::get('/init-machines-table/{id}', 'MachineController@getMachinesTableData')->middleware('auth:acs_admin,acs_manager,acs_viewer,customer_admin,customer_manager,customer_operator');
	Route::post('/devices-for-dashboard-table', 'DeviceController@getDashboardMachinesTable')->middleware('auth:acs_admin,acs_manager,acs_viewer,customer_admin,customer_manager,customer_operator');
});

Route::group(['prefix' => 'customers'], function () {
	Route::get('/', 'CompanyController@index')->middleware('auth:acs_admin,acs_manager,acs_viewer');
	Route::post('/add', 'CompanyController@addCustomer')->middleware('auth:acs_admin,acs_manager');
	Route::get('/{id}', 'CompanyController@getCustomer')->middleware('auth:acs_admin,acs_manager');
	Route::post('/update-account/{id}', 'CompanyController@updateCustomerAccount')->middleware('auth:acs_admin,acs_manager');
	Route::post('/update-profile/{id}', 'CompanyController@updateCustomerProfile')->middleware('auth:acs_admin,acs_manager');
});

Route::group(['prefix' => 'companies'], function () {
	Route::get('/', 'CompanyController@getCompanies')->middleware('auth:acs_admin,acs_manager,acs_viewer');
});

Route::group(['prefix' => 'devices'], function () {
	Route::post('/', 'DeviceController@getACSDevices')->middleware('auth:acs_admin,acs_manager,acs_viewer');
	Route::post('/import', 'DeviceController@importDevices')->middleware('auth:acs_admin,acs_manager');
	Route::post('/device-assigned', 'DeviceController@deviceAssigned')->middleware('auth:acs_admin,acs_manager');
	Route::post('/device-register-update', 'DeviceController@updateRegistered')->middleware('auth:acs_admin,acs_manager');
	Route::post('/suspend-device', 'DeviceController@suspendDevice')->middleware('auth:acs_admin,acs_manager');

	Route::post('/query-sim/{iccid}', 'DeviceController@querySIM')->middleware('auth:acs_admin,acs_manager');
	Route::post('/suspend-sim/{iccid}', 'DeviceController@suspendSIM')->middleware('auth:acs_admin,acs_manager');
	Route::post('/remote-web/{deviceid}', 'DeviceController@remoteWeb')->middleware('auth:acs_admin,acs_manager');
	Route::post('/remote-cli/{deviceid}', 'DeviceController@remoteCli')->middleware('auth:acs_admin,acs_manager');
});

Route::group(['prefix' => 'analytics'], function () {
	Route::get('/product-overview/{id}', 'MachineController@getProductOverview');
	Route::post('/product-utilization', 'MachineController@getProductUtilization');
	Route::post('/product-energy-consumption', 'MachineController@getEnergyConsumption');
	Route::get('/product-inventory/{id}', 'MachineController@getInventories');
	Route::get('/product-station-conveyings/{id}', 'MachineController@getStationConveyings');
	Route::get('/weekly-running-hours/{id}', 'MachineController@getWeeklyRunningHours');
	Route::get('/product-weight/{id}', 'MachineController@getProductWeight');
	Route::get('/product-current-recipe/{id}', 'MachineController@getCurrentRecipe');
	Route::post('/blender/process-rate', 'MachineController@getBlenderProcessRate');
	Route::post('/blender/calibration-factors', 'MachineController@getBDBlenderCalibrationFactors');
	Route::post('/accumeter/blender-capabilities', 'MachineController@getBlenderCapabilities');
	Route::post('/accumeter/feeder-calibrations', 'MachineController@getFeederCalibrations');
	Route::post('/accumeter/feeder-speeds', 'MachineController@getFeederSpeeds');
	Route::post('/accumeter/target-rate', 'MachineController@getTargetRate');
	Route::get('/product-system-states/{id}', 'MachineController@getProductStates');
	Route::get('/product-hopper-stables/{id}', 'MachineController@getHopperStables');
	Route::get('/product-system-states-3/{id}', 'MachineController@getMachineStates3');
	Route::get('/product-feeder-stables/{id}', 'MachineController@getFeederStables');
	Route::post('/product-production-rate', 'MachineController@getProductProcessRate');
	Route::post('/product-hopper-inventories', 'MachineController@getInventories3');
	Route::post('/product-hauloff-lengths', 'MachineController@getHauloffLengths');
	Route::get('/product-actual-target-recipe/{id}', 'MachineController@getTgtActualRecipes');
	Route::get('/vtc-plus/pump-onlines/{id}', 'MachineController@getPumpOnlines');
	Route::get('/vtc-plus/pump-blowbacks/{id}', 'MachineController@getPumpBlowBacks');
	Route::get('/product-pump-hours-oil/{id}', 'MachineController@getPumpHoursOil');
	Route::get('/product-pump-hours/{id}', 'MachineController@getPumpHours');
	Route::get('/product-drying-hopper-states/{id}', 'MachineController@getDryingHopperStates');
	Route::get('/product-hopper-temperatures/{id}', 'MachineController@getHopperTemperatures');

	Route::get('/ngx-dryer/bed-states/{id}', 'MachineController@getNgxDryerBedStates');
	Route::post('/ngx-dryer/dh-online-hours', 'MachineController@getNgxDryerDhOnlineHours');
	Route::post('/ngx-dryer/dryer-online-hours', 'MachineController@getNgxDryerDryerOnlineHours');
	Route::post('/ngx-dryer/blower-run-hours', 'MachineController@getNgxDryerBlowerRunHours');

	Route::get('/tcu/actual-target-temperature/{id}', 'MachineController@getTcuActTgtTemperature');
});

Route::group(['prefix' => 'notes'], function () {
	Route::post('/store', 'NoteController@store');
	Route::get('/{device_id}', 'NoteController@getNotes');
});

Route::group(['prefix' => 'alarms'], function () {
	Route::post('/alarms-for-customer-devices', 'AlarmController@getAlarmsForCustomerDevices')->middleware('auth:customer_admin,customer_manager,customer_operator');
	Route::post('/{product_id}', 'AlarmController@getProductAlarms');
	Route::get('/alarms-by-company-id/{company_id}', 'AlarmController@getAlarmsByCompanyId');
	Route::post('/severity-by-company-id', 'AlarmController@getSeverityByCompanyId');
	Route::post('/alarms-per-type-by-machine', 'AlarmController@getAlarmsPerTypeByMachine');
	Route::post('/alarms-distribution-by-machine', 'AlarmController@getAlarmsDistributionByMachine');
	Route::post('/alarms-amount-per-machine-by-company-id', 'AlarmController@getAlarmsAmountPerMachineByCompanyId');
});

Route::group(['prefix' => 'cities'], function () {
	Route::get('/{state}', 'CityController@citiesForState');
});

Route::group(['prefix' => 'settings'], function () {
	Route::get('/app-settings', 'SettingController@appSettings');
});

Route::post('test/send-mail', 'CompanyController@testMail');
Route::post('test/send-sms', 'CompanyController@testSMS');
Route::post('test/blender-json', 'TestController@store');

Route::post('test/azure', 'DeviceController@testAzureJson');
Route::post('test/mqtt', 'DeviceController@testMqttPHP');
Route::post('test/carrier/{id}', 'DeviceController@carrierFromKoreAPI');

Route::get('test/pusher-notification', 'DeviceController@sendEvent');
