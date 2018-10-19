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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/register', 'UserController@store');
Route::post('/login', 'AuthController@login');

Route::middleware('auth:api')->group(function () {
    Route::get('/tags', 'TagController@index');
    Route::post('/tags', 'TagController@store');
});

Route::middleware('auth:api', 'idAdmin')->group(function () {
//    Route::get('/users', 'UserController@index');
    Route::get('/tags/count', 'TagController@count');
    Route::get('/tags/all', 'TagController@all');
});