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

Route::middleware('auth')->group(function () {
    Route::get('/auth/logout', 'UserController@logout');
	Route::post('/auth/update-password', 'UserController@updatePassword');
});

Route::group(['middleware' => 'auth:customer_admin'], function () {
	Route::group(['prefix' => 'devices'], function () {
		Route::get('/customer-devices', 'DeviceController@getCustomerDevices');
	});
});

Route::group(['middleware' => 'auth:acs_admin'], function () {
	Route::group(['prefix' => 'customers'], function () {
		Route::get('/', 'CompanyController@index')->name('customers');
		Route::post('/add', 'CompanyController@addCustomer')->name('customers.store');
		Route::get('/{id}', 'CompanyController@getCustomer')->name('customers.show');
		Route::post('/update-account/{id}', 'CompanyController@updateCustomerAccount');
		Route::post('/update-profile/{id}', 'CompanyController@updateCustomerProfile')->name('customers.update.profile');
	});

	Route::group(['prefix' => 'devices'], function () {
		Route::get('/{pageNum}', 'DeviceController@getDevices')->name('devices');
		Route::post('/import', 'DeviceController@importDevices');
		Route::post('/device-assigned', 'DeviceController@deviceAssigned')->name('devices.device.assigned');
		Route::post('/device-register-update', 'DeviceController@updateRegistered')->name('devices.update.registered');
		Route::post('/suspend-device', 'DeviceController@suspendDevice');

		Route::post('/query-sim/{iccid}', 'DeviceController@querySIM');
		Route::post('/suspend-sim/{iccid}', 'DeviceController@suspendSIM');
	});
});



Route::get('/zones', 'ZoneController@index');
Route::post('/zones/add', 'ZoneController@store');
Route::patch('/zones/update', 'ZoneController@update');

Route::post('test/send-mail', 'CompanyController@testMail');
Route::post('test/blender-json', 'TestController@store');
