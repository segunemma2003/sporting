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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('post', 'PostController@index');
Route::get('fixture', 'FixtureController@index');

//Route::middleware('auth:api')->group(function () {
//    Route::apiResources([
//        'post' => 'PostController',
//        'fixture' => 'FixtureController',
//    ]);
//});

//Route::get('post_data', function (Request $request) {
//    return DB::select(DB::raw('select column_name, column_type from information_schema.columns where table_name=\'fixtures\' AND table_schema=\'sporting\';'));
//});

Route::post('login', 'LoginController@login');
Route::post('register', 'RegisterController@register');

