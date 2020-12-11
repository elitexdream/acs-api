<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Authentication api routes
Route::post('auth/check', 'UserController@check');
Route::post('auth/signin', 'UserController@login');
Route::post('auth/signup', 'UserController@register');
Route::post('auth/password-reset', 'UserController@passwordReset');

Route::get('/configurations/index', 'MachineController@getAllConfigurations');

Route::middleware('auth')->group(function () {
    Route::get('/auth/logout', 'UserController@logout');
	Route::post('/auth/update-password', 'UserController@updatePassword');
});

Route::group(['prefix' => 'locations'], function () {
	Route::get('/', 'LocationController@index')->middleware('auth:customer_admin,customer_manager');
	Route::post('/add', 'LocationController@store')->middleware('auth:customer_admin,customer_manager');
	Route::patch('/update', 'LocationController@update')->middleware('auth:customer_admin,customer_manager');
});

Route::get('/locations-zones', 'ZoneController@initLocationsAndZones')->middleware('auth:customer_admin,customer_manager');

Route::group(['prefix' => 'zones'], function () {
	Route::get('/', 'ZoneController@index')->middleware('auth:customer_admin,customer_manager');
	Route::post('/add', 'ZoneController@store')->middleware('auth:customer_admin,customer_manager');
	Route::patch('/update', 'ZoneController@update')->middleware('auth:customer_admin,customer_manager');
});

Route::group(['prefix' => 'devices'], function () {
	Route::get('/customer-devices', 'DeviceController@getCustomerDevices')->middleware('auth:customer_admin,customer_manager,customer_operator');
	Route::get('/customer-devices-analytics', 'DeviceController@getCustomerDevicesAnalytics')->middleware('auth:customer_admin,customer_manager,customer_operator');
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

Route::group(['prefix' => 'acs-machines'], function () {
	Route::get('/', 'MachineController@index')->middleware('auth:acs_admin,acs_manager,acs_viewer,customer_admin,customer_manager,customer_operator');
	Route::get('/get-machines', 'MachineController@getMachines')->middleware('auth:acs_admin,acs_manager,acs_viewer,customer_admin,customer_manager,customer_operator');
	Route::get('/init-locations-table', 'MachineController@getLocationsTableData')->middleware('auth:acs_admin,acs_manager,acs_viewer,customer_admin,customer_manager,customer_operator');
});

Route::group(['prefix' => 'customers'], function () {
	Route::get('/', 'CompanyController@index')->middleware('auth:acs_admin,acs_manager,acs_viewer');
	Route::get('/init-add-company', 'CompanyController@getCompanies')->middleware('auth:acs_admin,acs_manager');
	Route::post('/add', 'CompanyController@addCustomer')->middleware('auth:acs_admin,acs_manager');
	Route::get('/{id}', 'CompanyController@getCustomer')->middleware('auth:acs_admin,acs_manager');
	Route::post('/update-account/{id}', 'CompanyController@updateCustomerAccount')->middleware('auth:acs_admin,acs_manager');
	Route::post('/update-profile/{id}', 'CompanyController@updateCustomerProfile')->middleware('auth:acs_admin,acs_manager');
});

Route::group(['prefix' => 'devices'], function () {
	Route::get('/{pageNum}', 'DeviceController@getDevices')->middleware('auth:acs_admin,acs_manager');
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
	Route::get('/weekly-running-hours/{id}', 'MachineController@getWeeklyRunningHours');
	Route::post('/init-product', 'MachineController@initProductPage');
	Route::post('/product-weight', 'MachineController@getProductWeight');
	Route::post('/product-inventory', 'MachineController@getProductInventory');
});
Route::group(['prefix' => 'notes'], function () {
	Route::post('/store', 'NoteController@store');
	Route::get('/index/{machine_id}', 'NoteController@getMachineNotes');
});

Route::group(['prefix' => 'alarms'], function () {
	Route::post('/', 'AlarmController@getProductAlarms');
	Route::get('/alarm-types/{machine_id}', 'AlarmController@getCorrespondingAlarmTypes');
});

Route::group(['prefix' => 'cities'], function () {
	Route::get('/{state}', 'CityController@citiesForState');
});

Route::post('test/send-mail', 'CompanyController@testMail');
Route::post('test/send-sms', 'CompanyController@testSMS');
Route::post('test/blender-json', 'TestController@store');

Route::post('test/azure', 'DeviceController@testAzureJson');
Route::post('test/mqtt', 'DeviceController@testMqttPHP');
Route::post('test/carrier/{id}', 'DeviceController@carrierFromKoreAPI');