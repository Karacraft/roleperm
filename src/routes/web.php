<?php

use Karacraft\RolesAndPermissions\Http\Controllers\RoleController;
use Karacraft\RolesAndPermissions\Http\Controllers\MethodController;
use Karacraft\RolesAndPermissions\Http\Controllers\PermissionController;

Route::resource('role', RoleController::class);
Route::resource('permission', PermissionController::class);
Route::resource('method', MethodController::class);

Route::get('package', function() {
    return 'Role & Permission package is working';
});
