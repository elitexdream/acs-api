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

Route::group(['prefix' => 'customers', 'middleware' => 'auth:acs_admin'], function () {
// Route::group(['prefix' => 'customers'], function () {
	Route::get('/', 'CompanyController@index')->name('customers');
	Route::post('/add', 'CompanyController@addCustomer')->name('customers.store');
	Route::get('/{id}', 'CompanyController@getCustomer')->name('customers.show');
	Route::post('/update-account/{id}', 'CompanyController@updateCustomerAccount');
	Route::post('/update-profile/{id}', 'CompanyController@updateCustomerProfile')->name('customers.update.profile');

});

Route::group(['middleware' => 'auth:acs_admin'], function () {
	Route::get('/devices/{pageNum}', 'DeviceController@getDevices')->name('devices');
	Route::post('/devices/upload', 'DeviceController@uploadDevices')->name('devices.uplad');
	Route::post('/devices/device-assigned', 'DeviceController@deviceAssigned')->name('devices.device.assigned');
	Route::post('/devices/device-register-update', 'DeviceController@updateRegistered')->name('devices.update.registered');
	Route::post('/devices/suspend-device', 'DeviceController@suspendDevice');

});

Route::get('/zones', 'ZoneController@index');
Route::post('/zones/add', 'ZoneController@store');
Route::patch('/zones/update', 'ZoneController@update');

Route::post('test/send-mail', 'CompanyController@testMail');
Route::post('test/blender-json', 'TestController@store');
