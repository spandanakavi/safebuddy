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
    Route::resource('buddies', 'Api\v1\BuddyController', ['only' => [
        'index', 'show', 'store', 'update', 'destroy'
    ]]);
    Route::get('users/{id}/contacts', 'Api\v1\UserController@contacts');
    Route::resource('trips', 'Api\v1\TripController', ['only' => [
        'index', 'show'
    ]]);
    Route::post('users/me/track', 'Api\v1\UserController@track');
    Route::post('users/me/sos', 'Api\v1\UserController@sos');
});
