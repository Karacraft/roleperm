<?php

namespace Karacraft\RolesAndPermissions\Console\Commands;

use Illuminate\Console\Command;
use Karacraft\RolesAndPermissions\Support\PermissionRegistry;

class ClearPermissions extends Command
{
    protected $signature = 'roleperm:clear';

    protected $description = 'Clear the cached roles and permissions map';

    public function __construct(protected PermissionRegistry $registry)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->registry->flush();

        $this->info('Roles and permissions cache cleared.');

        return self::SUCCESS;
    }
}
