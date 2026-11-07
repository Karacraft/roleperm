<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackageTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tables = [
            'users' => config('roles-and-permissions.tables.users', 'users'),
            'roles' => config('roles-and-permissions.tables.roles', 'roles'),
            'permissions' => config('roles-and-permissions.tables.permissions', 'permissions'),
            'methods' => config('roles-and-permissions.tables.methods', 'methods'),
            'roles_permissions' => config('roles-and-permissions.tables.roles_permissions', 'roles_permissions'),
            'users_roles' => config('roles-and-permissions.tables.users_roles', 'users_roles'),
            'users_permissions' => config('roles-and-permissions.tables.users_permissions', 'users_permissions'),
        ];

        $foreignKeys = [
            'user' => config('roles-and-permissions.foreign_keys.user', 'user_id'),
            'role' => config('roles-and-permissions.foreign_keys.role', 'role_id'),
            'permission' => config('roles-and-permissions.foreign_keys.permission', 'permission_id'),
        ];

        $guard = config('roles-and-permissions.default_guard')
            ?? (config('roles-and-permissions.guards')[0] ?? config('auth.defaults.guard', 'web'));

        // Methods Table
        Schema::create($tables['methods'], function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug');
        });

        // Permissions Table
        Schema::create($tables['permissions'], function (Blueprint $table) use ($guard) {
            $table->id();
            $table->string('title');
            $table->string('slug');
            $table->string('guard_name')->default($guard)->index();
            $table->string('model')->nullable();  // Users
            $table->string('method')->nullable(); // view,create,update,delete,edit,show
            $table->unique(['guard_name', 'title']);
            $table->unique(['guard_name', 'slug']);
        });

        // Roles Table
        Schema::create($tables['roles'], function (Blueprint $table) use ($guard) {
            $table->id();
            $table->string('title');
            $table->string('slug');
            $table->string('guard_name')->default($guard)->index();
            $table->string('description')->default('Role Description');
            $table->unique(['guard_name', 'title']);
            $table->unique(['guard_name', 'slug']);
        });

        // Roles Permissions Table
        Schema::create($tables['roles_permissions'], function (Blueprint $table) use ($tables, $foreignKeys) {
            $table->foreignId($foreignKeys['role'])->constrained($tables['roles'])->cascadeOnDelete();
            $table->foreignId($foreignKeys['permission'])->constrained($tables['permissions'])->cascadeOnDelete();

            // SETTING THE PRIMARY KEYS
            $table->primary([$foreignKeys['role'], $foreignKeys['permission']]);
        });

        // Users Permissions Table
        Schema::create($tables['users_permissions'], function (Blueprint $table) use ($tables, $foreignKeys) {
            $table->foreignId($foreignKeys['user'])->constrained($tables['users'])->cascadeOnDelete();
            $table->foreignId($foreignKeys['permission'])->constrained($tables['permissions'])->cascadeOnDelete();

            // SETTING THE PRIMARY KEYS
            $table->primary([$foreignKeys['user'], $foreignKeys['permission']]);
        });

        // Users Roles Table
        Schema::create($tables['users_roles'], function (Blueprint $table) use ($tables, $foreignKeys) {
            $table->foreignId($foreignKeys['user'])->constrained($tables['users'])->cascadeOnDelete();
            $table->foreignId($foreignKeys['role'])->constrained($tables['roles'])->cascadeOnDelete();

            // SETTING THE PRIMARY KEYS
            $table->primary([$foreignKeys['user'], $foreignKeys['role']]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(config('roles-and-permissions.tables.users_roles', 'users_roles'));
        Schema::dropIfExists(config('roles-and-permissions.tables.users_permissions', 'users_permissions'));
        Schema::dropIfExists(config('roles-and-permissions.tables.roles_permissions', 'roles_permissions'));
        Schema::dropIfExists(config('roles-and-permissions.tables.roles', 'roles'));
        Schema::dropIfExists(config('roles-and-permissions.tables.permissions', 'permissions'));
        Schema::dropIfExists(config('roles-and-permissions.tables.methods', 'methods'));
    }
}
