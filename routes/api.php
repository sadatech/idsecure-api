<?php

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

Route::post('authenticate', 'AuthenticateController@authenticate');
Route::post('register', 'AuthenticateController@register');


Route::group(['middleware' => ['jwt.auth']], function () {
    Route::get('reports', 'ReportController@index');
    Route::get('reports/{user}', 'ReportController@show');
});