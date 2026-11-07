# Laravel Roles & Permissions Package

[![Laravel](https://img.shields.io/badge/Laravel-11.x%20to%2013.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.4+-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

A comprehensive, flexible role-based and permission-based authorization package for Laravel applications. Designed to work seamlessly with Laravel Jetstream and provide granular access control for your applications.

## Features

- **Role-Based Access Control (RBAC)**: Assign roles to users with predefined permissions
- **Granular Permissions**: Control access at the method level (Create, Read, Update, Delete, etc.)
- **Middleware Protection**: Protect routes and controllers with role/permission checks
- **Trait Integration**: Easy integration with User models via traits
- **Opt-in Seeding**: Seed methods, permissions, and roles; optionally create a super admin with your own credentials
- **Flexible Configuration**: Customize permission methods, models, tables, and guards via config
- **Cached Gate Integration**: Permissions are resolved lazily and cached, with artisan commands to rebuild/clear
- **Laravel Jetstream Compatible**: Optimized for use with Laravel Jetstream authentication

## Requirements

- PHP 8.4 or higher
- Laravel 11.x to 13.x
- Composer

## Installation

### Step 1: Add Repository to Composer

Since this package is not yet published on Packagist, you need to add the repository to your `composer.json`:

```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/Karacraft/roleperm",
            "options": {
                "symlink": true
            }
        }
    ]
}
```

### Step 2: Install Package

```bash
composer require karacraft/roleperm
```

### Step 3: Publish Package Resources

Publish the necessary configuration, migrations, and seeders:

```bash
php artisan vendor:publish --provider="Karacraft\RolesAndPermissions\RolesAndPermissionsServiceProvider"
```

This will publish:
- Configuration file (`config/roles-and-permissions.php`)
- Database migrations
- Seeders
- Traits

Alternatively, you can publish specific resources:

```bash
# Publish configuration
php artisan vendor:publish --tag=RolesAndPermissions-config

# Publish migrations
php artisan vendor:publish --tag=RolesAndPermissions-migrations

# Publish seeders
php artisan vendor:publish --tag=RolesAndPermissions-seeders

# Publish traits
php artisan vendor:publish --tag=RolesAndPermissions-traits
```

### Step 4: Run Migrations

```bash
php artisan migrate
```

### Step 5: Seed Database

Seed the permission methods, permissions, and roles:

```bash
php artisan roleperm:seed
```

To also create/update the configured super admin user, enable it in config (see
[Super Admin Seeding](#super-admin-seeding-opt-in)) or pass the flag:

```bash
php artisan roleperm:seed --super-admin
```

If you published the seeder, Laravel's seeder command works too:

```bash
php artisan db:seed --class=RolesAndPermissionsSeeder
```

> **Note**: The seeder is idempotent. No user is created unless you explicitly
> enable super admin seeding and provide credentials.

## Configuration

After publishing, edit `config/roles-and-permissions.php` to customize:

### Permission Methods
Define the available permission methods:

```php
'permission-methods' => [
    'Create', 'Update', 'Delete', 'Edit', 'Show', 'List', 'View', 'Tile',
    'Approve', 'Reject', 'Cancel', 'Hold'
],
```

### Super Admin Seeding (opt-in)

No user is created by default and there are no default credentials. To have the
seeder create/update a super admin user, enable it and supply credentials:

```php
'seed' => [
    'super_admin' => [
        'enabled' => env('ROLEPERM_SUPER_ADMIN_ENABLED', false),
        'name' => env('ROLEPERM_SUPER_ADMIN_NAME', 'Super Admin'),
        'email' => env('ROLEPERM_SUPER_ADMIN_EMAIL'),
        'password' => env('ROLEPERM_SUPER_ADMIN_PASSWORD'),
        'role' => 'Super Admin',
    ],
],
```

You can also enable it for a single run with:

```bash
php artisan roleperm:seed --super-admin
```

If seeding is enabled but the email or password is missing, the command fails
with a clear error instead of creating an insecure account.

### User Model

The package never hard-codes your application's user model. By default it uses
`config('auth.providers.users.model')`, but you can point it at any model:

```php
'user_model' => App\Models\User::class,
```

### Models

Override the package models if you need to extend them:

```php
'role_model' => Karacraft\RolesAndPermissions\Models\Role::class,
'permission_model' => Karacraft\RolesAndPermissions\Models\Permission::class,
'method_model' => Karacraft\RolesAndPermissions\Models\Method::class,
```

### Tables & Foreign Keys

Table and pivot column names are configurable so the package can coexist with
your application's schema:

```php
'tables' => [
    'users' => 'users',
    'roles' => 'roles',
    'permissions' => 'permissions',
    'methods' => 'methods',
    'roles_permissions' => 'roles_permissions',
    'users_roles' => 'users_roles',
    'users_permissions' => 'users_permissions',
],

'foreign_keys' => [
    'user' => 'user_id',
    'role' => 'role_id',
    'permission' => 'permission_id',
],
```

### Guards

Roles and permissions are scoped by `guard_name`:

```php
'guards' => ['web'],
'default_guard' => null, // defaults to the first guard, then auth.defaults.guard
```

### Permission Models

Specify which models should have permissions generated during seeding:

```php
'permission-models' => [
    'User', 'Role', 'Permission', 'Method'
],
```

### Caching

Permissions are not queried from the database during application boot. The
package registers a single `Gate::before` callback and resolves the permission
map lazily, caching it so repeated `can()` checks stay cheap:

```php
'cache' => [
    'enabled' => env('ROLEPERM_CACHE_ENABLED', true),
    'store' => env('ROLEPERM_CACHE_STORE'), // null = default cache store
    'key' => 'roles-and-permissions.permissions',
    'ttl' => env('ROLEPERM_CACHE_TTL', 3600), // null = forever
],
```

Manage the cache with:

```bash
# Rebuild the cached permission map (run during deployment)
php artisan roleperm:cache

# Clear the cached permission map
php artisan roleperm:clear
```

The cache is also invalidated automatically whenever a permission is created,
updated, or deleted through the package's `Permission` model.

## Usage

### 1. Update User Model

Add the trait to your `User` model (`app/Models/User.php`). The trait provides the `roles()` and `permissions()` relationships. Implement the contracts too so your model can be type-hinted anywhere:

```php
<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Karacraft\RolesAndPermissions\Contracts\HasPermissions;
use Karacraft\RolesAndPermissions\Contracts\HasRoles;
use Karacraft\RolesAndPermissions\Traits\HasPermissionTrait;

class User extends Authenticatable implements HasRoles, HasPermissions
{
    use HasPermissionTrait;

    // ... other model code ...
}
```

### 2. Using in Controllers

Check permissions in your controllers:

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        // Check if user has permission to view posts
        if (!$request->user()->can('list_post')) {
            abort(403, 'Unauthorized');
        }

        // Your code here
    }

    public function create(Request $request)
    {
        // Check if user has permission to create posts
        if (!$request->user()->can('create_post')) {
            abort(403, 'Unauthorized');
        }

        // Your code here
    }
}
```

### 3. Protecting Routes with Middleware

Apply the role middleware to routes:

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

Route::middleware(['auth', 'role:super_admin'])->group(function () {
    Route::resource('posts', PostController::class);
});

// Or allow any of several roles
Route::middleware(['auth', 'role:super_admin|user'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
});

// Optionally require a permission as the second parameter
Route::middleware(['auth', 'role:super_admin|user,create_post'])->group(function () {
    Route::post('/posts', [PostController::class, 'store']);
});
```

### 4. Blade Templates

Use permission checks in Blade templates:

```blade
@if(auth()->user()->can('create_post'))
    <a href="{{ route('posts.create') }}" class="btn btn-primary">Create Post</a>
@endif

@if(auth()->user()->hasRole('super_admin'))
    <div class="admin-panel">
        <!-- Admin content -->
    </div>
@endif
```

The package also provides `@roles` / `@endroles`, `@role` / `@endrole`, and a
`@tocurrency` directive.

### 5. Gate Integration

Every permission is available through Laravel's `Gate`, so `can()`, `@can`,
and `Gate::allows()` work without defining anything manually. A single
`Gate::before` callback resolves the cached permission map and delegates to
`hasPermissionTo()` on your user model. Unknown abilities are left untouched so
your own policies and gates continue to work.

## Available Methods

### User Model Methods (via HasPermissionTrait)

- `hasRole(...$roles)`: Check if user has a specific role (slug or title)
- `hasAnyRole(...$roles)`: Check if user has any of the specified roles
- `hasPermissionTo($permission)`: Check if user has a permission (model, slug, title, or id)
- `assignRole($role)`: Assign a role to the user (model, slug, title, or id)
- `removeRole($role)`: Remove a role from the user
- `givePermissionsTo(...$permissions)`: Give one or more permissions to the user
- `givePermissionTo($permission)`: Give a single permission to the user
- `withdrawPermissionsTo(...$permissions)`: Remove one or more permissions from the user
- `revokePermissionTo($permission)`: Revoke a single permission from the user
- `refreshPermissions(...$permissions)`: Replace the user's permissions

> Permission slugs are normalized to snake_case, e.g. `Create Post` becomes `create_post`.
> Use that slug with `can()` and the `role` middleware. Permission names are resolved by slug or title.

## Database Structure

The package creates the following tables (all names are configurable):

- `methods`: Stores permission methods (Create, Update, etc.)
- `permissions`: Stores permissions (model + method combinations), scoped by `guard_name`
- `roles`: Stores user roles, scoped by `guard_name`
- `roles_permissions`: Pivot table for role-permission relationships
- `users_permissions`: Pivot table for user-permission relationships
- `users_roles`: Pivot table for user-role relationships

Uniqueness is enforced per guard: `(guard_name, slug)` and `(guard_name, title)`
for both roles and permissions.

## Testing

The package is tested with PHPUnit and [Orchestra Testbench](https://packages.tools/testbench)
against Laravel 11, 12, and 13.

Run the full suite:

```bash
composer test
```

Run with a coverage report (requires Xdebug or PCOV):

```bash
composer test:coverage
```

The suite covers models and slug/guard defaults, the `HasPermissionTrait` API,
Gate integration, the permission cache, seeding (including opt-in super admin),
console commands, middleware, and Blade directives.

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## License

This package is open-sourced software licensed under the [MIT license](LICENSE).

## Support

If you encounter any issues or need help, please open an issue on GitHub.

## Author

**Karacraft** - [kcraftpk@gmail.com](mailto:kcraftpk@gmail.com)

---

*Built with ❤️ for the Laravel community*  

