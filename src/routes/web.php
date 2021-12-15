<?php

use Karacraft\Roleperm\Http\Controllers\RoleController;

// Route::group(['namespace' => 'Karacraft\Roleperm\Http\Controllers'],function(){

    //  Role
    Route::name('role.')->prefix('role/')->group(function(){
        // Route::put('{id}',[ RoleController::class, 'update'])->name('update');
        // Route::get('{id}/edit',[ RoleController::class, 'edit'])->name('edit');
        // Route::resource('/',  RoleController::class  , ['only' => ['index','create','show']] );
        // Route::resource('/', RoleController::class)->only(['index','show','update','edit','create']);
        Route::get('masterdata',[RoleController::class, 'masterData'])->name('masterdata');

    });

// });

Route::get('role',[ RoleController::class , 'index'])->name('role.index');

Route::get('package', function() {
    return 'Its Working';
});
