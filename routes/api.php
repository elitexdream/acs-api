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
	Route::get('/', 'CustomerController@index')->name('customers');
	Route::post('/add', 'CustomerController@addCustomer')->name('customers.store');
	Route::get('/{id}', 'CustomerController@getCustomer')->name('customers.show');
	Route::post('/update-account/{id}', 'CustomerController@updateCustomerAccount');
	Route::post('/update-profile/{id}', 'CustomerController@updateCustomerProfile')->name('customers.update.profile');

});

Route::group(['middleware' => 'auth:acs_admin'], function () {
	Route::get('/devices', 'DeviceController@getDevices')->name('devices');
	Route::post('/devices/upload', 'DeviceController@uploadDevices')->name('devices.uplad');
});

Route::post('test/send-mail', 'CustomerController@testMail');
Route::post('test/blender-json', 'TestController@store');