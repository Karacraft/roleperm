<?php

use Karacraft\RolesAndPermissions\Models\Method;
use Karacraft\RolesAndPermissions\Models\Permission;
use Karacraft\RolesAndPermissions\Models\Role;

return [

    /**
     * The model used for users (auth subjects). When null, the package falls
     * back to config('auth.providers.users.model').
     */
    'user_model' => null,

    /**
     * Models used by the package. Override these to extend the defaults.
     */
    'role_model' => Role::class,
    'permission_model' => Permission::class,
    'method_model' => Method::class,

    /**
     * Database table names used by the package and its migrations.
     */
    'tables' => [
        'users' => 'users',
        'roles' => 'roles',
        'permissions' => 'permissions',
        'methods' => 'methods',
        'roles_permissions' => 'roles_permissions',
        'users_roles' => 'users_roles',
        'users_permissions' => 'users_permissions',
    ],

    /**
     * Pivot foreign key column names.
     */
    'foreign_keys' => [
        'user' => 'user_id',
        'role' => 'role_id',
        'permission' => 'permission_id',
    ],

    /**
     * Guards that roles and permissions may belong to.
     */
    'guards' => ['web'],

    /**
     * Default guard used when none is provided. Falls back to the first guard
     * above, then config('auth.defaults.guard').
     */
    'default_guard' => null,

    /**
     * Permission cache used by the Gate integration. The cache is rebuilt with
     * `php artisan roleperm:cache` and cleared with `php artisan roleperm:clear`.
     */
    'cache' => [
        'enabled' => env('ROLEPERM_CACHE_ENABLED', true),
        'store' => env('ROLEPERM_CACHE_STORE'), // null = default cache store
        'key' => 'roles-and-permissions.permissions',
        'ttl' => env('ROLEPERM_CACHE_TTL', 3600), // null = forever
    ],

    /**
     * These methods will be seeded, so add whatever you like and then seed the database
     */
    'permission-methods' => [
        'Create','Update','Delete','Edit','Show','List','View','Tile','Approve','Reject','Cancel','Hold'
    ],

    /**
     * Super admin user seeding is opt-in. When enabled, the seeder will create
     * or update the user with these credentials and grant it every permission.
     * There are intentionally no default credentials.
     */
    'seed' => [
        'super_admin' => [
            'enabled' => env('ROLEPERM_SUPER_ADMIN_ENABLED', false),
            'name' => env('ROLEPERM_SUPER_ADMIN_NAME', 'Super Admin'),
            'email' => env('ROLEPERM_SUPER_ADMIN_EMAIL'),
            'password' => env('ROLEPERM_SUPER_ADMIN_PASSWORD'),
            'role' => 'Super Admin',
        ],
    ],

    /**
     * Number of items per page for the permission list.
     */
    'paging-number' => 5,

    /**
     * Message to display when a user is unauthorized.
     */
    'unauthorized_access_string' => 'Unauthorized Access',
];
