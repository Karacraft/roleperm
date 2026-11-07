<?php

namespace Karacraft\RolesAndPermissions\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Karacraft\RolesAndPermissions\Support\Config;
use Karacraft\RolesAndPermissions\Support\PermissionRegistry;

class Permission extends Base
{
    use HasFactory;

    protected $fillable = ['title','slug','model','method','guard_name'];

    protected static function booted(): void
    {
        parent::booted();

        static::creating(function ($permission) {
            if (empty($permission->guard_name)) {
                $permission->guard_name = Config::defaultGuard();
            }
        });

        static::saved(function () {
            static::flushPermissionCache();
        });

        static::deleted(function () {
            static::flushPermissionCache();
        });
    }

    protected static function flushPermissionCache(): void
    {
        if (app()->bound(PermissionRegistry::class)) {
            app(PermissionRegistry::class)->flush();
        }
    }

    public function getTable(): string
    {
        return Config::table('permissions');
    }

    /** Relationships */
    public function roles()
    {
        return $this->belongsToMany(
            Config::roleModel(),
            Config::table('roles_permissions'),
            Config::foreignKey('permission'),
            Config::foreignKey('role')
        );
    }

    public function users()
    {
        return $this->belongsToMany(
            Config::userModel(),
            Config::table('users_permissions'),
            Config::foreignKey('permission'),
            Config::foreignKey('user')
        );
    }
}
