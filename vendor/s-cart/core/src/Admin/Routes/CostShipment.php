<?php
if (file_exists(app_path('Admin/Controllers/AdminCostShipController.php'))) {
    $nameSpaceAdminCostShip = 'App\Admin\Controllers';
} else {
    $nameSpaceAdminCostShip = 'SCart\Core\Admin\Controllers';
}
Route::group(['prefix' => 'costship'], function () use ($nameSpaceAdminCostShip) {
    Route::get('/', $nameSpaceAdminCostShip.'\AdminCostShipController@index')->name('admin_costship.index');
    Route::get('create', function () {
        return redirect()->route('admin_costship.index');
    });
    Route::post('/create', $nameSpaceAdminCostShip.'\AdminCostShipController@postCreate')->name('admin_costship.create');
    Route::get('/edit/{id}', $nameSpaceAdminCostShip.'\AdminCostShipController@edit')->name('admin_costship.edit');
    Route::post('/edit/{id}', $nameSpaceAdminCostShip.'\AdminCostShipController@postEdit')->name('admin_costship.edit');
    Route::post('/delete', $nameSpaceAdminCostShip.'\AdminCostShipController@deleteList')->name('admin_costship.delete');
});
