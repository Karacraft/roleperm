# Role & Permission Package

Simple Role & Permission based Authorization package for Laravel 8.x.
To be used with Laravel JetStream

## Installation
Currently, the package is not on packagist. To install it on your own project , add the following in your main project composer.json , just below "script" 

    "repositories": [{
        "type" : "vcs",
        "url": "https://github.com/Karacraft/RolesAndPermissions",
        "options": {
            "symlink": true
        }
    }],

Then

    composer require karacraft/RolesAndPermissions

    or 

    composer update

You can use 

    php artisan vendor:publish 

to get the following

-   Karacraft\RolesAndPermissions\RolesAndPermissionsServiceProvider
-   RolesAndPermissions-helpers
-   RolesAndPermissions-migrations
-   RolesAndPermissions-seeders
-   RolesAndPermissions-traits
-   RolesAndPermissions-config

## Usage  

First of All, Publish the following **Necessary**

    RolesAndPermissions-config (Holds all methods for Permissions, Update here before seeding)  
    RolesAndPermissions-seeders (Creates all Methods, Permissions & Basic SuperAdmin & User Role) - Super Admin is created here, update it.  Ensure user with same name doesn't exists or else it will throw error.  

Add following relationships to User Model  

    public function roles(){ return $this->belongsToMany(Role::class,'users_roles');}  
    public function permissions(){ return $this->belongsToMany(Permission::class,'users_permissions');}  

Also Add   

    Trait HasPermissionTrait to User Model  
        
Seed your database  

