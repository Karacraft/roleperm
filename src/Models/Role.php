<?php

namespace Karacraft\RolesAndPermissions\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Karacraft\RolesAndPermissions\Support\Config;

class Role extends Base
{
    use HasFactory;

    protected $fillable = ['title','description','slug','guard_name'];

    protected static function booted(): void
    {
        parent::booted();

        static::creating(function ($role) {
            if (empty($role->guard_name)) {
                $role->guard_name = Config::defaultGuard();
            }
        });
    }

    public function getTable(): string
    {
        return Config::table('roles');
    }

    /** Relationships */
    public function permissions()
    {
        return $this->belongsToMany(
            Config::permissionModel(),
            Config::table('roles_permissions'),
            Config::foreignKey('role'),
            Config::foreignKey('permission')
        );
    }

    public function users()
    {
        return $this->belongsToMany(
            Config::userModel(),
            Config::table('users_roles'),
            Config::foreignKey('role'),
            Config::foreignKey('user')
        );
    }
}
