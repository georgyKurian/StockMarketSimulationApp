<?php

use App\Http\Controllers\DayController;
use App\Http\Controllers\SimulationController;
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

Route::get('/', [SimulationController::class, 'index'])->name('index');

Route::get('/simulations', [SimulationController::class, 'index'])->name('simulations');
Route::get('/simulations/{simulation}', [SimulationController::class, 'show'])->name('simulations.show');

Route::get('/days/{day}', [DayController::class, 'show'])->name('days.show');



