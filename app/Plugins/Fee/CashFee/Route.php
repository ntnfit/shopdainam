<?php
/**
 * Route admin
 */
Route::group(
    [
        'prefix' => SC_ADMIN_PREFIX.'/cod_fee',
        'middleware' => SC_ADMIN_MIDDLEWARE,
        'namespace' => 'App\Plugins\Fee\CashFee\Admin',
    ], 
    function () {
        Route::post('/create', 'AdminController@postCreate')
            ->name('admin_cod_fee.create');
        Route::post('/remove', 'AdminController@removeRange')
            ->name('admin_cod_fee.remove');
    }
);
