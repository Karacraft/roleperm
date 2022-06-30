<?php

use Karacraft\RolesAndPermissions\Http\Controllers\RoleController;
use Karacraft\RolesAndPermissions\Http\Controllers\MethodController;
use Karacraft\RolesAndPermissions\Http\Controllers\PermissionController;

Route::name('role.')->prefix('role/')->group(function(){
    Route::resource('/', RoleController::class);
});
Route::name('permission.')->prefix('permission/')->group(function(){
    Route::resource('/', PermissionController::class);
});
Route::name('method.')->prefix('method/')->group(function(){
    Route::resource('/', MethodController::class);
});

Route::get('package', function() {
    return 'Role & Permission package is working';
});
