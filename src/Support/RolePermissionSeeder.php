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

        DB::transaction(function () use ($roleModel) {
            $roleModel::updateOrCreate(
                ['title' => 'Super Admin', 'guard_name' => $this->guard],
                ['slug' => 'Super Admin', 'description' => 'Application Owner']
            );

            $roleModel::updateOrCreate(
                ['title' => 'User', 'guard_name' => $this->guard],
                ['slug' => 'User', 'description' => 'Restricted User']
            );
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

        $roleName = $config['role'] ?? 'Super Admin';
        $roleSlug = Str::slug($roleName, '_');

        $userModel = Config::userModel();
        $roleModel = Config::roleModel();
        $permissionModel = Config::permissionModel();

        DB::transaction(function () use ($config, $email, $password, $roleName, $roleSlug, $userModel, $roleModel, $permissionModel) {
            $user = $userModel::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $config['name'] ?? 'Super Admin',
                    'password' => Hash::make($password),
                ]
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
}
