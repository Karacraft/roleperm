<?php

namespace Karacraft\RolesAndPermissions\Tests\Fixtures;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Karacraft\RolesAndPermissions\Contracts\HasPermissions;
use Karacraft\RolesAndPermissions\Contracts\HasRoles;
use Karacraft\RolesAndPermissions\Traits\HasPermissionTrait;

class User extends Authenticatable implements HasRoles, HasPermissions
{
    use HasPermissionTrait;

    protected $table = 'users';

    protected $fillable = ['name', 'email', 'password'];

    public $timestamps = false;
}
