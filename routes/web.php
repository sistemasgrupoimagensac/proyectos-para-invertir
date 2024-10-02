<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\DetalleInmuebleController;
use App\Http\Controllers\FavoritosController;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\MiMegustaController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\UbicacionController;

Route::get('/', function () {
    return view('login.login');
})->middleware('guest')->name('login1');

Auth::routes();

Route::get('reset-password', [ResetPasswordController::class, 'reset'])->name('reset.password');
Route::post('enviar-password-reset', [ResetPasswordController::class, 'sendPassword'])->name('enviar-password-reset');

Route::middleware(['auth'])->group(function () {
    // Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/home',[InicioController::class,'index'])->name('inicio');
    Route::get('/detalle/{id}',[DetalleInmuebleController::class,'index'])->name('detalle');
    Route::post('/me_interesa',[InicioController::class,'meInteresa'])->name('meInteresa');
    Route::post('/logout', [LoginController::class,'logout'])->name('logout');
    Route::get('/ubicacion',[UbicacionController::class,'index'])->name('ubicacion');
    Route::get('/favoritos',[FavoritosController::class,'index'])->name('favoritos');
    Route::post('/quitar_favoritos',[FavoritosController::class,'cambiarEstado'])->name('quitar_favoritos');
    Route::get('/perfil',[PerfilController::class,'index'])->name('perfil');
    Route::post('cambiar-password', [PerfilController::class,'updatePassword'])->name('cambiar-password');
});