# Role & Permission Package

Simple Role & Permission based Authorization package.

## Installation
Currently, the package is not on packagist. To install it on your own project , add the following in your main project composer.json , just below "script" 

    "repositories": [{
        "type" : "vcs",
        "url": "https://github.com/Karacraft/roleperm",
        "options": {
            "symlink": true
        }
    }],

Then

    composer require karacraft/roleperm

    or 

    composer update

You can use 

    php artisan vendor:publish 

to get the following

-   Karacraft\Roleperm\RolePermServiceProvider
-   roleperm-helpers
-   roleperm-migrations
-   roleperm-seeders
-   roleperm-traits
