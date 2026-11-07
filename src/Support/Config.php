<?php

namespace Karacraft\RolesAndPermissions\Support;

use Karacraft\RolesAndPermissions\Models\Method;
use Karacraft\RolesAndPermissions\Models\Permission;
use Karacraft\RolesAndPermissions\Models\Role;

class Config
{
    public static function get(string $key, mixed $default = null): mixed
    {
        return config('roles-and-permissions.' . $key, $default);
    }

    public static function userModel(): string
    {
        $model = self::get('user_model') ?: config('auth.providers.users.model');

        if (! is_string($model) || $model === '') {
            throw new \RuntimeException(
                'Unable to resolve the user model. Set "user_model" in config/roles-and-permissions.php '
                . 'or configure auth.providers.users.model.'
            );
        }

        return $model;
    }

    public static function roleModel(): string
    {
        return self::get('role_model', Role::class);
    }

    public static function permissionModel(): string
    {
        return self::get('permission_model', Permission::class);
    }

    public static function methodModel(): string
    {
        return self::get('method_model', Method::class);
    }

    public static function table(string $key): string
    {
        return self::get('tables.' . $key, $key);
    }

    public static function foreignKey(string $key): string
    {
        return self::get('foreign_keys.' . $key, $key . '_id');
    }

    public static function usersTable(): string
    {
        return self::table('users');
    }

    /**
     * @return array<int, string>
     */
    public static function guards(): array
    {
        $guards = self::get('guards', []);

        return is_array($guards) ? array_values($guards) : [];
    }

    public static function defaultGuard(): string
    {
        return self::get('default_guard')
            ?: (self::guards()[0] ?? config('auth.defaults.guard', 'web'));
    }

    public static function cacheEnabled(): bool
    {
        return filter_var(self::get('cache.enabled', true), FILTER_VALIDATE_BOOLEAN);
    }

    public static function cacheStore(): ?string
    {
        $store = self::get('cache.store');

        return is_string($store) && $store !== '' ? $store : null;
    }

    public static function cacheKey(): string
    {
        return self::get('cache.key', 'roles-and-permissions.permissions');
    }

    public static function cacheTtl(): ?int
    {
        $ttl = self::get('cache.ttl');

        return is_numeric($ttl) ? (int) $ttl : null;
    }

    /**
     * Models that permissions should be generated for while seeding.
     *
     * @return array<int, string>
     */
    public static function permissionModels(): array
    {
        $models = self::get('permission-models') ?? self::get('models', []);

        return is_array($models) ? array_values($models) : [];
    }

    /**
     * Super admin user seeding configuration. Disabled unless explicitly
     * enabled and credentials are supplied.
     *
     * @return array<string, mixed>
     */
    public static function superAdmin(): array
    {
        $config = self::get('seed.super_admin', []);

        if (! is_array($config)) {
            return [];
        }

        $config['enabled'] = filter_var($config['enabled'] ?? false, FILTER_VALIDATE_BOOLEAN);

        return $config;
    }
}
