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

Route::prefix('/projects')->group(function() {
    Route::get('/', 'Api\AllProjectsController@getAll');
    Route::get('/{id}', 'Api\ProjectDetailsController@getOne');
});

Route::get('/provincias', 'Api\ProvinciasController@getAll');