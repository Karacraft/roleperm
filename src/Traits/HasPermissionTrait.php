<?php

namespace Karacraft\RolesAndPermissions\Traits;

use Illuminate\Support\Arr;
use Karacraft\RolesAndPermissions\Models\Role;
use Karacraft\RolesAndPermissions\Models\Permission;
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

        return Permission::whereIn('slug', $permissions)
            ->orWhereIn('title', $permissions)
            ->get();
    }

    //  Find a permission by model, slug or title
    protected function resolvePermission($permission)
    {
        if ($permission instanceof Permission) {
            return $permission;
        }

        if (is_numeric($permission)) {
            return Permission::find($permission);
        }

        return Permission::where('slug', $permission)
            ->orWhere('title', $permission)
            ->first();
    }

    //  Does User have a permisison to act via role
    public function hasPermissionTo($permission)
    {
        $permission = $this->resolvePermission($permission);

        if ($permission === null) {
            return false;
        }

        return $this->hasPermissionThroughRole($permission) || $this->hasPermission($permission);
    }

    //  Does user have a permission to do action via role
    public function hasPermissionThroughRole($permission)
    {
        $permission = $this->resolvePermission($permission);

        if ($permission === null) {
            return false;
        }

        foreach ($permission->roles as $role){
            if($this->roles()->where('roles.id', $role->getKey())->exists()) {
                return true;
            }
        }
        return false;
    }
    //  Does this user have particular Permission
    protected function hasPermission($permission)
    {
        return $this->permissions()->where('permissions.slug', $permission->slug)->exists();
    }


    //  Does User have particualr role?
    public function hasRole( ... $roles )
    {
        $roles = Arr::flatten($roles);

        foreach ($roles as $role) {
            if ($this->roles()->where(function ($query) use ($role) {
                $query->where('roles.slug', $role)->orWhere('roles.title', $role);
            })->exists())
                return true;
        }
        return false;
    }

    //  Does User have any of the given roles?
    public function hasAnyRole( ... $roles )
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
        if ($role instanceof Role) {
            return $role;
        }

        if (is_numeric($role)) {
            return Role::find($role);
        }

        return Role::where('slug', $role)
            ->orWhere('title', $role)
            ->first();
    }

    //  User Can Have Many Roles
    public function roles() {return $this->belongsToMany(Role::class,'users_roles');}
    //  User Can have many permissions
    public function permissions() {return $this->belongsToMany(Permission::class,'users_permissions');}
}
