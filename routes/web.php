<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    return view('home');
});

Auth::routes();
Route::post('register', 'Auth\RegisterController@create');

Route::get('/home', 'HomeController@index');
Route::get('admin/home', 'AdminController@index');
Route::get('manager/home', 'ManagerController@index');
Route::get('view', 'ManagerController@show');

Route::get('/trips', 'TripController@show');
