<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EventController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('events', [EventController::class, 'store']);
Route::put('events/{uuid}', [EventController::class, 'update'])->middleware('uuid.exists');
Route::get('events', [EventController::class, 'list']);
Route::delete('events/{uuid}', [EventController::class, 'delete'])->middleware('uuid.exists');
