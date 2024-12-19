<?php

use App\Http\Controllers\Api\AprobarProyectoController;
use App\Http\Controllers\Api\LikeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('cors')->group(function() {
    Route::post('/login', 'Api\AuthController@login');
    Route::post('/reset-password', 'Api\ResetPasswordController@sendPassword');

    Route::middleware('auth:api')->group(function() {
        Route::post('/logout', 'Api\AuthController@logout');
        Route::put('/change-password', 'Api\AuthController@changePassword');
        Route::get('/user', function(Request $request) {
            return $request->user();
        });

        // Route::post('/like', [LikeController::class, 'meInteresa']);
        // Route::post('/dislike', [LikeController::class, 'noMeInteresa']);
        // Route::post('/aceptar-proyecto', [AprobarProyectoController::class, 'aceptar_proyecto']);

        Route::prefix('/projects')->group(function() {
            Route::get('/', 'Api\AllProjectsController@getAll');
            Route::get('/{id}', 'Api\ProjectDetailsController@getOne');
        });
        
        Route::get('/provincias', 'Api\ProvinciasController@getAll');
    });
});