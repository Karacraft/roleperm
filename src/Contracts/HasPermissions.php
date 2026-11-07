<?php

namespace Karacraft\RolesAndPermissions\Contracts;

interface HasPermissions
{
    public function permissions();

    public function hasPermissionTo($permission): bool;

    public function givePermissionsTo(...$permissions);

    public function givePermissionTo($permission);

    public function withdrawPermissionsTo(...$permissions);

    public function revokePermissionTo($permission);

    public function refreshPermissions(...$permissions);
}
