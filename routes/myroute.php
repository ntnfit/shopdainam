<?php

use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Customize web Routes
|--------------------------------------------------------------------------
|
 */
Route::get('/get_states', [App\Http\Controllers\StateController::class,'getAllStates'])->name('get_states');
Route::get('/getcost', [App\Http\Controllers\getcostshipvn::class,'getCost'])->name('getcost');
Route::get('/cancel-nl', [App\Plugins\Payment\Nganluong\Controllers\FrontController::class,'cancel'])->name('cancel_nganluong');
Route::get('/process-nl', [App\Plugins\Payment\Nganluong\Controllers\FrontController::class,'success'])->name('process_nl');