<?php
/**
 * Route admin
 */
Route::group(
    [
        'prefix' => SC_ADMIN_PREFIX.'/payment_fee',
        'middleware' => SC_ADMIN_MIDDLEWARE,
        'namespace' => 'App\Plugins\Fee\PaymentFee\Admin',
    ], 
    function () {
        Route::post('/create', 'AdminController@postCreate')
            ->name('admin_payment_fee.create');
        Route::post('/remove', 'AdminController@removeMethod')
            ->name('admin_payment_fee.remove');
    }
);
