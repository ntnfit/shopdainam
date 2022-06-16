<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Customize web Routes
|--------------------------------------------------------------------------
|
 */
Route::get('/get_states', [App\Http\Controllers\StateController::class,'getAllStates']);
Route::get('/getcost', [App\Http\Controllers\getcostshipvn::class,'getCost']);