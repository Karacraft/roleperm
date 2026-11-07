<?php

namespace Karacraft\RolesAndPermissions\Contracts;

interface HasRoles
{
    public function roles();

    public function hasRole(...$roles): bool;

    public function hasAnyRole(...$roles): bool;

    public function assignRole($role);

    public function removeRole($role);
}
