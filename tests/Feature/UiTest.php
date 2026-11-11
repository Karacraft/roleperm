<?php

namespace Karacraft\RolesAndPermissions\Tests\Feature;

use Karacraft\RolesAndPermissions\Http\Livewire\Dashboard;
use Karacraft\RolesAndPermissions\Models\Permission;
use Karacraft\RolesAndPermissions\Models\Role;
use Karacraft\RolesAndPermissions\Tests\Fixtures\User;
use Karacraft\RolesAndPermissions\Tests\TestCase;
use Livewire\Livewire;

class UiTest extends TestCase
{
    public function test_dashboard_route_is_registered(): void
    {
        $this->assertSame(url('roles-and-permissions'), route('roles-and-permissions.dashboard'));
    }

    public function test_dashboard_renders_roles_and_permissions(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'secret',
        ]);

        Role::create(['title' => 'Admin']);
        Permission::create([
            'title' => 'Create User',
            'model' => 'User',
            'method' => 'Create',
        ]);

        $this->actingAs($user)
            ->get(route('roles-and-permissions.dashboard'))
            ->assertOk()
            ->assertSee('Roles & Permissions')
            ->assertSee('Admin');

        Livewire::actingAs($user)
            ->test(Dashboard::class)
            ->set('tab', 'permissions')
            ->assertSee('Create User');
    }
}
