<?php
/**
 * Route front
 */
if(sc_config('AuthorizeNet')) {
Route::group(
    [
        'prefix'    => 'plugin/authorizenet',
        'namespace' => 'App\Plugins\Payment\AuthorizeNet\Controllers',
    ],
    function () {
        Route::get('index', 'FrontController@index')
        ->name('authorizenet.index');
        Route::post('/payment', 'FrontController@payment')
        ->name('authorizenet.payment');
    }
);
}

