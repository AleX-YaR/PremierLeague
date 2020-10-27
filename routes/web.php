<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\GameController;

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

Route::get('/', [GameController::class, 'index'])->name('index');
Route::get('/all', [GameController::class, 'playAll'])->name('playAll');
Route::get('/{week}/edit', [GameController::class, 'edit'])->name('edit');
Route::put('/', [GameController::class, 'update'])->name('update');
Route::post('/', [GameController::class, 'nextWeek'])->name('nextWeek');
Route::delete('/', [GameController::class, 'reset'])->name('reset');
