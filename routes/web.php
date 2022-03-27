<?php

use Illuminate\Support\Facades\Route;

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


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/scrap', [App\Http\Controllers\HomeController::class, 'scrap'])->name('home.scrap');

Route::group(['prefix' => 'homes'], function () {
   Route::get('/data', [App\Http\Controllers\HomeController::class, 'data']);
   Route::get('/create', [App\Http\Controllers\HomeController::class, 'create'])->name('home.create');
   Route::post('/create', [App\Http\Controllers\HomeController::class, 'store']);
   Route::get('/{home}/edit', [App\Http\Controllers\HomeController::class, 'edit']);
   Route::put('/{home}/edit', [App\Http\Controllers\HomeController::class, 'update']);
   Route::delete('/{home}/delete', [App\Http\Controllers\HomeController::class, 'delete']);

});
Route::post('/upload-img', [App\Http\Controllers\HomeController::class, 'uploadImg']);
Route::post('/upload-multi-file', [App\Http\Controllers\HomeController::class, 'uploadMultiFile']);
