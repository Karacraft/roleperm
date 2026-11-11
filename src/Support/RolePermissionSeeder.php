<?php

namespace Karacraft\RolesAndPermissions\Support;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use RuntimeException;

class RolePermissionSeeder
{
    /**
     * @var array<int, string>
     */
    protected array $methods = [];

    /**
     * @var array<int, string>
     */
    protected array $models = [];

    protected string $guard;

    public function run(): void
    {
        $methods = Config::get('permission-methods', []);

        $this->methods = is_array($methods) ? array_values($methods) : [];
        $this->models = Config::permissionModels();
        $this->guard = Config::defaultGuard();

        $this->createBaseMethods();
        $this->createBasePermissions();
        $this->createBaseRoles();
        $this->seedSuperAdmin();
    }

    protected function createBaseMethods(): void
    {
        $methodModel = Config::methodModel();

        DB::transaction(function () use ($methodModel) {
            foreach ($this->methods as $method) {
                $methodModel::updateOrCreate(
                    ['title' => $method],
                    ['slug' => $method]
                );
            }
        });
    }

    protected function createBasePermissions(): void
    {
        $permissionModel = Config::permissionModel();

        DB::transaction(function () use ($permissionModel) {
            foreach ($this->models as $model) {
                foreach ($this->methods as $method) {
                    $title = $method . " " . $model;

                    $permissionModel::updateOrCreate(
                        ['title' => $title, 'guard_name' => $this->guard],
                        ['slug' => $title, 'method' => $method, 'model' => $model]
                    );
                }
            }
        });
    }

    protected function createBaseRoles(): void
    {
        $roleModel = Config::roleModel();
        $roles = Config::get('seed.roles', []);

        if (! is_array($roles)) {
            return;
        }

        DB::transaction(function () use ($roleModel, $roles) {
            foreach ($roles as $role) {
                $title = is_array($role) ? ($role['title'] ?? null) : $role;

                if (! is_string($title) || trim($title) === '') {
                    continue;
                }

                $attributes = ['slug' => $title];

                if (is_array($role) && isset($role['description'])) {
                    $attributes['description'] = $role['description'];
                }

                $roleModel::updateOrCreate(
                    ['title' => $title, 'guard_name' => $this->guard],
                    $attributes
                );
            }
        });
    }

    protected function seedSuperAdmin(): void
    {
        $config = Config::superAdmin();

        if (! ($config['enabled'] ?? false)) {
            return;
        }

        $email = $config['email'] ?? null;
        $password = $config['password'] ?? null;

        if (empty($email) || empty($password)) {
            throw new RuntimeException(
                'Super admin seeding is enabled, but "seed.super_admin.email" and '
                . '"seed.super_admin.password" are not configured.'
            );
        }

        $roleName = $config['role'] ?? null;

        if (! is_string($roleName) || trim($roleName) === '') {
            throw new RuntimeException(
                'Super admin seeding is enabled, but "seed.super_admin.role" is not configured.'
            );
        }

        $fields = is_array($config['fields'] ?? null) ? $config['fields'] : [];
        $nameField = $this->fieldName($fields, 'name');
        $emailField = $this->fieldName($fields, 'email');
        $passwordField = $this->fieldName($fields, 'password');

        $roleSlug = Str::slug($roleName, '_');

        $userModel = Config::userModel();
        $roleModel = Config::roleModel();
        $permissionModel = Config::permissionModel();

        $user = new $userModel();

        if (! method_exists($user, 'roles') || ! method_exists($user, 'permissions')) {
            throw new RuntimeException(
                "User model [{$userModel}] must use the HasPermissionTrait."
            );
        }

        DB::transaction(function () use ($config, $email, $password, $emailField, $nameField, $passwordField, $roleName, $roleSlug, $userModel, $roleModel, $permissionModel) {
            $attributes = [
                $nameField => $config['name'] ?? null,
                $passwordField => Hash::make($password),
            ];

            if ($attributes[$nameField] === null) {
                unset($attributes[$nameField]);
            }

            $user = $userModel::updateOrCreate(
                [$emailField => $email],
                $attributes
            );

            $role = $roleModel::where('guard_name', $this->guard)
                ->where(function ($query) use ($roleName, $roleSlug) {
                    $query->where('slug', $roleSlug)->orWhere('title', $roleName);
                })
                ->first();

            if ($role === null) {
                throw new RuntimeException(
                    "Unable to find role [{$roleName}] for guard [{$this->guard}]."
                );
            }

            $permissions = $permissionModel::where('guard_name', $this->guard)->get();

            $role->permissions()->syncWithoutDetaching($permissions->modelKeys());
            $user->roles()->syncWithoutDetaching([$role->getKey()]);
            $user->permissions()->syncWithoutDetaching($permissions->modelKeys());
        });
    }

    /**
     * @param array<string, mixed> $fields
     */
    protected function fieldName(array $fields, string $key): string
    {
        $field = $fields[$key] ?? $key;

        if (! is_string($field) || trim($field) === '') {
            throw new RuntimeException(
                "Super admin field mapping [{$key}] must be a non-empty string."
            );
        }

        return $field;
    }
}
