<?php
if (file_exists(app_path('Admin/Controllers/AdminCostServiceController.php'))) {
    $nameSpaceAdminCostService = 'App\Admin\Controllers';
} else {
    $nameSpaceAdminCostService = 'SCart\Core\Admin\Controllers';
}
Route::group(['prefix' => 'costservice'], function () use ($nameSpaceAdminCostService) {
    Route::get('/', $nameSpaceAdminCostService.'\AdminCostServiceController@index')->name('admin_costservice.index');
    Route::get('create', function () {
        return redirect()->route('admin_costservice.index');
    });
    Route::post('/create', $nameSpaceAdminCostService.'\AdminCostServiceController@postCreate')->name('admin_costservice.create');
    Route::get('/edit/{id}', $nameSpaceAdminCostService.'\AdminCostServiceController@edit')->name('admin_costservice.edit');
    Route::post('/edit/{id}', $nameSpaceAdminCostService.'\AdminCostServiceController@postEdit')->name('admin_costservice.edit');
    Route::post('/delete', $nameSpaceAdminCostService.'\AdminCostServiceController@deleteList')->name('admin_costservice.delete');
});
