<?php

use Karacraft\RolesAndPermissions\Models\Role;
use Karacraft\RolesAndPermissions\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can create a role and assign permissions to it', function () {
    $role = Role::create([
        'title' => 'Editor',
        'description' => 'Can edit content',
        'slug' => 'editor',
        'guard_name' => 'web',
    ]);

    $permission = Permission::create([
        'title' => 'Edit Content',
        'slug' => 'edit-content',
        'model' => 'Post',
        'method' => 'update',
        'guard_name' => 'web',
    ]);

    $role->permissions()->attach($permission->id);

    expect($role->permissions()->count())->toBe(1);
    expect($role->permissions()->first()->slug)->toBe('edit-content');
});

it('can check if a role has a specific permission', function () {
    $role = Role::create([
        'title' => 'Admin',
        'description' => 'Full access',
        'slug' => 'admin',
        'guard_name' => 'web',
    ]);

    $permission = Permission::create([
        'title' => 'Delete User',
        'slug' => 'delete-user',
        'model' => 'User',
        'method' => 'delete',
        'guard_name' => 'web',
    ]);

    $role->permissions()->attach($permission->id);

    // Assuming a helper method or registry check
    expect($role->hasPermission('delete-user'))->toBeTrue();
});

it('gracefully handles missing permissions in the registry', function () {
    $registry = app(\Karacraft\RolesAndPermissions\Support\PermissionRegistry::class);
    
    // Should return false instead of throwing an exception
    expect($registry->contains('non-existent-permission'))->toBeFalse();
});
