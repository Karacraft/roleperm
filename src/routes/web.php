<?php

use Illuminate\Support\Facades\Route;
use Karacraft\RolesAndPermissions\Http\Livewire\Dashboard;

$path = trim((string) config('roles-and-permissions.ui.path', 'roles-and-permissions'), '/');
$path = $path === '' ? 'roles-and-permissions' : $path;
$middleware = (array) config('roles-and-permissions.ui.middleware', ['web', 'auth']);

Route::middleware($middleware)->group(function () use ($path) {
    Route::get($path, Dashboard::class)->name('roles-and-permissions.dashboard');
});
