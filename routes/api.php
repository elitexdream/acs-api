<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('auth/check', 'UserController@check');
Route::post('auth/signin', 'UserController@login');
Route::post('auth/signup', 'UserController@register');
Route::post('auth/password-reset', 'UserController@passwordReset');

Route::middleware('auth')->group(function () {
    Route::get('/auth/logout', 'UserController@logout');
	Route::post('/auth/update-password', 'UserController@updatePassword');
});

Route::group(['middleware' => 'auth:customer_admin'], function () {
	Route::group(['prefix' => 'devices'], function () {
		Route::get('/customer-devices', 'DeviceController@getCustomerDevices');
	});

	Route::group(['prefix' => 'locations'], function () {
		Route::get('/', 'LocationController@index');
		Route::post('/add', 'LocationController@store');
		Route::patch('/update', 'LocationController@update');
	});

	Route::get('/locations-zones', 'ZoneController@initLocationsAndZones');
	
	Route::group(['prefix' => 'zones'], function () {
		Route::get('/', 'ZoneController@index');
		Route::post('/add', 'ZoneController@store');
		Route::patch('/update', 'ZoneController@update');
	});

	Route::group(['prefix' => 'company-users'], function () {
		Route::get('/', 'UserController@getCompanyUsers');
		Route::post('/store', 'UserController@addCompanyUser');
		Route::get('/init-create-account', 'UserController@initCreateAccount');
		Route::get('/init-edit-account/{id}', 'UserController@initEditAccount');
		Route::post('/update-account/{id}', 'UserController@updateCompanyUserAccount');
		Route::post('/update-information/{id}', 'UserController@updateCompanyUserInformation');
	});
});

Route::group(['prefix' => 'acs-users'], function () {
	Route::get('/', 'UserController@initAcsUsers')->middleware('auth:acs_admin,acs_manager,acs_viewer');
	Route::get('/init-create', 'UserController@initCreateAcsUser')->middleware('auth:acs_admin');
	Route::get('/init-edit/{id}', 'UserController@initEditAcsUser')->middleware('auth:acs_admin');
	Route::post('/store', 'UserController@addAcsUser')->middleware('auth:acs_admin');
	Route::post('/update-account/{id}', 'UserController@updateAcsUserAccount')->middleware('auth:acs_admin');
	Route::post('/update-information/{id}', 'UserController@updateAcsUserInformation')->middleware('auth:acs_admin');
});

Route::group(['prefix' => 'acs-machines'], function () {
	Route::get('/', 'MachineController@index')->middleware('auth:acs_admin,acs_manager,acs_viewer');
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
});

Route::group(['prefix' => 'analytics'], function () {
	Route::post('/init-product', 'MachineController@initProductPage');
	Route::post('/product-weight', 'MachineController@getProductWeight');
	Route::post('/product-inventory', 'MachineController@getProductInventory');
});

Route::group(['prefix' => 'alarms'], function () {
	Route::post('/', 'AlarmController@getProductAlarms');
});

Route::group(['prefix' => 'cities'], function () {
	Route::get('/{state}', 'CityController@citiesForState');
});

Route::post('test/send-mail', 'CompanyController@testMail');
Route::post('test/send-sms', 'CompanyController@testSMS');
Route::post('test/blender-json', 'TestController@store');

Route::get('test', function() {
	return 'ok';
});