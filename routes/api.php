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
});

Route::group(['middleware' => 'auth:acs_admin'], function () {
	Route::get('/customers', 'ACSAdminController@index');
	Route::post('/customers/add', 'ACSAdminController@addCustomer');
	Route::get('/customers/{id}', 'ACSAdminController@getCustomer');
	Route::post('/customers/update-account/{id}', 'ACSAdminController@updateCustomerAccount');
	Route::post('/customers/update-profile/{id}', 'ACSAdminController@updateCustomerProfile');
});