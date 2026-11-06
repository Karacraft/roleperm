# Laravel Roles & Permissions Package

[![Laravel](https://img.shields.io/badge/Laravel-8.x%20to%2013.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.1+-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

A comprehensive, flexible role-based and permission-based authorization package for Laravel applications. Designed to work seamlessly with Laravel Jetstream and provide granular access control for your applications.

## Features

- **Role-Based Access Control (RBAC)**: Assign roles to users with predefined permissions
- **Granular Permissions**: Control access at the method level (Create, Read, Update, Delete, etc.)
- **Middleware Protection**: Protect routes and controllers with role/permission checks
- **Trait Integration**: Easy integration with User models via traits
- **Database Seeding**: Automated setup of roles, permissions, and default users
- **Flexible Configuration**: Customize permission methods and models via config
- **Laravel Jetstream Compatible**: Optimized for use with Laravel Jetstream authentication

## Requirements

- PHP 8.1 or higher
- Laravel 8.x to 13.x
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

```bash
php artisan db:seed --class=RolesAndPermissionsSeeder
```

> **Note**: The seeder is idempotent. It will create or update the super admin user based on the configured email (default: `kc@somewhere.com`).

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

### Super Admin User
Configure the default super admin user:

```php
'user-info-1' => [
    'name' => 'Super Admin',
    'email' => 'admin@example.com',
    'password' => 'secure_password',
],
```

### Models
Specify which models should have permissions:

```php
'models' => [
    'User', 'Role', 'Permission', 'Method'
],
```

## Usage

### 1. Update User Model

Add the trait to your `User` model (`app/Models/User.php`). The trait provides the `roles()` and `permissions()` relationships:

```php
<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Karacraft\RolesAndPermissions\Traits\HasPermissionTrait;

class User extends Authenticatable
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

The package creates the following tables:

- `methods`: Stores permission methods (Create, Update, etc.)
- `permissions`: Stores permissions (model + method combinations)
- `roles`: Stores user roles
- `roles_permissions`: Pivot table for role-permission relationships
- `users_permissions`: Pivot table for user-permission relationships
- `users_roles`: Pivot table for user-role relationships

## Testing

Run the package tests:

```bash
composer test
```

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

