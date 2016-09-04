<?php

use Illuminate\Http\Request;

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

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::group(['prefix' => 'v1'], function () {
    Route::post('register', 'Api\v1\RegisterController@create');
    Route::resource('projects', 'Api\v1\ProjectController', ['only' => [
        'index', 'show'
    ]]);
    Route::resource('users', 'Api\v1\UserController', ['only' => [
        'index', 'show'
    ]]);
    Route::resource('vehicles', 'Api\v1\VehicleController', ['only' => [
        'index', 'show'
    ]]);
});
