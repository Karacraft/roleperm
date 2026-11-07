<?php

namespace Karacraft\RolesAndPermissions\Tests\Feature;

use Illuminate\Http\Request;
use Karacraft\RolesAndPermissions\Http\Middleware\RoleMiddleware;
use Karacraft\RolesAndPermissions\Models\Permission;
use Karacraft\RolesAndPermissions\Models\Role;
use Karacraft\RolesAndPermissions\Tests\Fixtures\User;
use Karacraft\RolesAndPermissions\Tests\TestCase;
use Symfony\Component\HttpKernel\Exception\HttpException;

class MiddlewareTest extends TestCase
{
    public function test_it_allows_users_with_the_required_role(): void
    {
        $user = $this->userWithRole('admin');

        $response = $this->runMiddleware($user, 'admin');

        $this->assertSame('ok', $response->getContent());
    }

    public function test_it_allows_any_role_from_the_list(): void
    {
        $user = $this->userWithRole('editor');

        $response = $this->runMiddleware($user, 'admin|editor');

        $this->assertSame('ok', $response->getContent());
    }

    public function test_it_denies_users_without_the_required_role(): void
    {
        $user = $this->makeUser();

        $this->expectException(HttpException::class);

        $this->runMiddleware($user, 'admin');
    }

    public function test_it_denies_users_without_the_required_permission(): void
    {
        $user = $this->userWithRole('admin');

        $this->expectException(HttpException::class);

        $this->runMiddleware($user, 'admin', 'create_user');
    }

    public function test_it_allows_users_with_the_required_permission(): void
    {
        $user = $this->userWithRole('admin');
        $user->givePermissionTo(Permission::create(['title' => 'Create User']));

        $response = $this->runMiddleware($user, 'admin', 'create_user');

        $this->assertSame('ok', $response->getContent());
    }

    private function runMiddleware(User $user, string $role, ?string $permission = null)
    {
        $request = Request::create('/protected', 'GET');
        $request->setUserResolver(fn () => $user);

        return (new RoleMiddleware())->handle($request, fn () => response('ok'), $role, $permission);
    }

    private function userWithRole(string $role): User
    {
        Role::create(['title' => $role]);

        $user = $this->makeUser();
        $user->assignRole($role);

        return $user;
    }

    private function makeUser(): User
    {
        return User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'secret',
        ]);
    }
}
