<?php

namespace Karacraft\RolesAndPermissions\Console\Commands;

use Illuminate\Console\Command;
use Karacraft\RolesAndPermissions\Support\RolePermissionSeeder;

class SeedRolesAndPermissions extends Command
{
    protected $signature = 'roleperm:seed
                            {--super-admin : Also create or update the configured super admin user}
                            {--force : Run in production without confirmation}';

    protected $description = 'Seed permission methods, permissions, and roles';

    public function handle(RolePermissionSeeder $seeder): int
    {
        if ($this->getLaravel()->environment('production') && ! $this->option('force')) {
            $this->error('Refusing to seed in production. Re-run with --force.');

            return self::FAILURE;
        }

        if ($this->option('super-admin')) {
            config(['roles-and-permissions.seed.super_admin.enabled' => true]);
        }

        try {
            $seeder->run();
        } catch (\Throwable $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        $this->info('Roles and permissions seeded successfully.');

        return self::SUCCESS;
    }
}
