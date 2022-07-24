<?php
/**
 * Route front
 */
if(sc_config('Nganluong')) {
Route::group(
    [
        'prefix'    => 'plugin/nganluong',
        'namespace' => 'App\Plugins\Payment\Nganluong\Controllers',
    ],
    function () {
        Route::get('index', 'FrontController@index')
        ->name('nganluong.index');
    }
);
}
/**
 * Route admin
 */
Route::group(
    [
        'prefix' => SC_ADMIN_PREFIX.'/nganluong',
        'middleware' => SC_ADMIN_MIDDLEWARE,
        'namespace' => 'App\Plugins\Payment\Nganluong\Admin',
    ], 
    function () {
        Route::get('/', 'AdminController@index')
        ->name('admin_nganluong.index');
    }
);
