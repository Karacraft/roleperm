<?php

namespace Karacraft\RolesAndPermissions\Traits;

use Illuminate\Support\Arr;
use Karacraft\RolesAndPermissions\Support\Config;

/**
 * Has Permission to View/Edit/Add/Delete Model Items
 */
trait HasPermissionTrait
{
    //  Based on : https://www.codecheef.org/article/user-roles-and-permissions-tutorial-in-laravel-without-packages
    /**
     * Saves new Permissions to a user
     */
    public function givePermissionsTo(... $permissions)
    {
        $permissions = $this->getAllPermissions($permissions);

        if($permissions->isEmpty()) {
          return $this;
        }

        $this->permissions()->syncWithoutDetaching($permissions->modelKeys());
        return $this;
    }

    /**
     * Saves a single Permission to a user
     */
    public function givePermissionTo($permission)
    {
        return $this->givePermissionsTo($permission);
    }

    /**
     * Remove Permissions from a User
     */
    public function withdrawPermissionsTo( ... $permissions )
    {
        $permissions = $this->getAllPermissions($permissions);
        $this->permissions()->detach($permissions->modelKeys());
        return $this;
    }

    /**
     * Remove a single Permission from a User
     */
    public function revokePermissionTo($permission)
    {
        return $this->withdrawPermissionsTo($permission);
    }

    /**
     * Refresh the Permissions
     */
    public function refreshPermissions( ... $permissions )
    {
        $this->permissions()->detach();
        return $this->givePermissionsTo($permissions);
    }

    //  Get all the permissions
    protected function getAllPermissions(array $permissions)
    {
        $permissions = Arr::flatten($permissions);
        $permissionModel = Config::permissionModel();

        $ids = [];
        $names = [];

        foreach ($permissions as $permission) {
            if ($permission instanceof $permissionModel) {
                $ids[] = $permission->getKey();
            } elseif (is_numeric($permission)) {
                $ids[] = (int) $permission;
            } elseif (is_string($permission) && $permission !== '') {
                $names[] = $permission;
            }
        }

        if ($ids === [] && $names === []) {
            return $permissionModel::query()->whereRaw('0 = 1')->get();
        }

        $keyName = (new $permissionModel())->getKeyName();

        $guard = Config::userGuard();

        return $permissionModel::query()
            ->where('guard_name', $guard)
            ->when($ids !== [], fn ($query) => $query->whereIn($keyName, $ids))
            ->when($names !== [], function ($query) use ($ids, $names) {
                $method = $ids === [] ? 'where' : 'orWhere';

                $query->{$method}(function ($query) use ($names) {
                    $query->whereIn('slug', $names)->orWhereIn('title', $names);
                });
            })
            ->get();
    }

    //  Find a permission by model, slug or title
    protected function resolvePermission($permission)
    {
        $permissionModel = Config::permissionModel();
        $guard = Config::userGuard();

        if ($permission instanceof $permissionModel) {
            return $permission->guard_name === $guard ? $permission : null;
        }

        if (is_numeric($permission)) {
            return $permissionModel::where('guard_name', $guard)
                ->find($permission);
        }

        return $permissionModel::where('guard_name', $guard)
            ->where(function ($query) use ($permission) {
                $query->where('slug', $permission)->orWhere('title', $permission);
            })
            ->first();
    }

    //  Does User have a permisison to act via role
    public function hasPermissionTo($permission): bool
    {
        $permission = $this->resolvePermission($permission);

        if ($permission === null) {
            return false;
        }

        return $this->hasPermissionThroughRole($permission) || $this->hasPermission($permission);
    }

    //  Does user have a permission to do action via role
    public function hasPermissionThroughRole($permission): bool
    {
        $permission = $this->resolvePermission($permission);

        if ($permission === null) {
            return false;
        }

        $rolesTable = $this->roles()->getRelated()->getTable();
        $guard = Config::userGuard();

        $roles = $permission->roles()
            ->where($rolesTable.'.guard_name', $guard)
            ->get();

        foreach ($roles as $role) {
            if ($this->roles()->where($rolesTable.'.id', $role->getKey())->exists()) {
                return true;
            }
        }
        return false;
    }
    //  Does this user have particular Permission
    protected function hasPermission($permission): bool
    {
        $table = $this->permissions()->getRelated()->getTable();

        return $this->permissions()
            ->where($table.'.slug', $permission->slug)
            ->where($table.'.guard_name', Config::userGuard())
            ->exists();
    }


    //  Does User have particualr role?
    public function hasRole( ... $roles ) : bool
    {
        $roles = Arr::flatten($roles);
        $table = $this->roles()->getRelated()->getTable();
        $guard = Config::userGuard();

        foreach ($roles as $role) {
            if ($this->roles()
                ->where($table.'.guard_name', $guard)
                ->where(function ($query) use ($role, $table) {
                    $query->where($table.'.slug', $role)->orWhere($table.'.title', $role);
                })
                ->exists())
                return true;
        }
        return false;
    }

    //  Does User have any of the given roles?
    public function hasAnyRole( ... $roles ) : bool
    {
        return $this->hasRole(...$roles);
    }

    //  Assign a role to the user
    public function assignRole($role)
    {
        $role = $this->resolveRole($role);

        if ($role !== null) {
            $this->roles()->syncWithoutDetaching([$role->getKey()]);
        }

        return $this;
    }

    //  Remove a role from the user
    public function removeRole($role)
    {
        $role = $this->resolveRole($role);

        if ($role !== null) {
            $this->roles()->detach($role->getKey());
        }

        return $this;
    }

    //  Find a role by model, slug or title
    protected function resolveRole($role)
    {
        $roleModel = Config::roleModel();
        $guard = Config::userGuard();

        if ($role instanceof $roleModel) {
            return $role->guard_name === $guard ? $role : null;
        }

        if (is_numeric($role)) {
            return $roleModel::where('guard_name', $guard)->find($role);
        }

        return $roleModel::where('guard_name', $guard)
            ->where(function ($query) use ($role) {
                $query->where('slug', $role)->orWhere('title', $role);
            })
            ->first();
    }

    //  User Can Have Many Roles
    public function roles()
    {
        return $this->belongsToMany(
            Config::roleModel(),
            Config::table('users_roles'),
            Config::foreignKey('user'),
            Config::foreignKey('role')
        );
    }

    //  User Can have many permissions
    public function permissions()
    {
        return $this->belongsToMany(
            Config::permissionModel(),
            Config::table('users_permissions'),
            Config::foreignKey('user'),
            Config::foreignKey('permission')
        );
    }
}
