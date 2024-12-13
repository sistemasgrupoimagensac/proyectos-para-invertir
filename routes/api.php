<?php

use App\Http\Controllers\Api\LikeController;
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


Route::middleware(['cors'])->group(function() {
    Route::post('/like',[LikeController::class, 'meInteresa']);
    Route::post('/dislike',[LikeController::class, 'noMeInteresa']);
    Route::prefix('/projects')->group(function() {
        Route::get('/', 'Api\AllProjectsController@getAll');
        Route::get('/{id}', 'Api\ProjectDetailsController@getOne');
    });
    
    Route::get('/provincias', 'Api\ProvinciasController@getAll');
});

