<?php

use Karacraft\Roleperm\Http\Controllers\RoleController;

Route::name('role.')->prefix('role/')->group(function(){
    Route::put('{id}',[ RoleController::class, 'update'])->name('update');
    Route::get('{id}/edit',[ RoleController::class, 'edit'])->name('edit');
    // Route::resource('/',  RoleController::class  , ['only' => ['index','create','show']] );
    Route::get('master',[ RoleController::class, 'getMasterData'])->name('master');
    Route::resource('/', RoleController::class)->only(['index','show','store','create']);

});


// Route::get('role',[ RoleController::class , 'index'])->name('role.index');

Route::get('package', function() {
    return 'Role & Permission package is working';
});
