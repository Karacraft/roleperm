<?php

namespace Karacraft\RolesAndPermissions\Tests\Feature;

use Illuminate\Support\Facades\Blade;
use Karacraft\RolesAndPermissions\Models\Role;
use Karacraft\RolesAndPermissions\Tests\Fixtures\User;
use Karacraft\RolesAndPermissions\Tests\TestCase;

class BladeDirectivesTest extends TestCase
{
    public function test_role_directive(): void
    {
        $this->actingAs($this->userWithRole('admin'));

        $this->assertSame('Yes', trim(Blade::render("@role(\"admin\")\nYes\n@endrole")));
        $this->assertSame('', trim(Blade::render("@role(\"user\")\nYes\n@endrole")));
    }

    public function test_roles_directive(): void
    {
        $this->actingAs($this->userWithRole('admin'));

        $this->assertSame('Yes', trim(Blade::render("@roles(\"admin\")\nYes\n@endroles")));
        $this->assertSame('Yes', trim(Blade::render("@roles(\"user\", \"admin\")\nYes\n@endroles")));
        $this->assertSame('', trim(Blade::render("@roles(\"user\")\nYes\n@endroles")));
    }

    public function test_role_directives_are_guest_safe(): void
    {
        $this->assertSame('', trim(Blade::render("@role(\"admin\")\nYes\n@endrole")));
        $this->assertSame('', trim(Blade::render("@roles(\"admin\")\nYes\n@endroles")));
    }

    public function test_to_currency_directive(): void
    {
        $this->assertSame('1,234.50', trim(Blade::render('@tocurrency(1234.5)')));
    }

    private function userWithRole(string $role): User
    {
        Role::create(['title' => $role]);

        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'secret',
        ]);

        $user->assignRole($role);

        return $user;
    }
}
