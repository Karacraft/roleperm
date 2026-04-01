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

> **Note**: Before seeding, update the configuration file and ensure no user exists with the same email as the super admin (default: `kc@somewhere.com`).

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

Add the required relationships and trait to your `User` model (`app/Models/User.php`):

```php
<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Karacraft\RolesAndPermissions\Traits\HasPermissionTrait;

class User extends Authenticatable
{
    use HasPermissionTrait;

    // ... other model code ...

    /**
     * User roles relationship
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'users_roles');
    }

    /**
     * User permissions relationship
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'users_permissions');
    }
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
        if (!$request->user()->can('List Post')) {
            abort(403, 'Unauthorized');
        }

        // Your code here
    }

    public function create(Request $request)
    {
        // Check if user has permission to create posts
        if (!$request->user()->can('Create Post')) {
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

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('posts', PostController::class);
});

// Or with specific permissions
Route::middleware(['auth', 'role:admin,editor'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
});
```

### 4. Blade Templates

Use permission checks in Blade templates:

```blade
@if(auth()->user()->can('Create Post'))
    <a href="{{ route('posts.create') }}" class="btn btn-primary">Create Post</a>
@endif

@if(auth()->user()->hasRole('admin'))
    <div class="admin-panel">
        <!-- Admin content -->
    </div>
@endif
```

## Available Methods

### User Model Methods (via HasPermissionTrait)

- `hasRole($role)`: Check if user has a specific role
- `hasAnyRole($roles)`: Check if user has any of the specified roles
- `can($permission)`: Check if user has a specific permission
- `assignRole($role)`: Assign a role to the user
- `removeRole($role)`: Remove a role from the user
- `givePermissionTo($permission)`: Give a permission to the user
- `revokePermissionTo($permission)`: Revoke a permission from the user

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

