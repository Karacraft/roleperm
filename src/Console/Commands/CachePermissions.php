<?php

namespace Karacraft\RolesAndPermissions\Console\Commands;

use Illuminate\Console\Command;
use Karacraft\RolesAndPermissions\Support\PermissionRegistry;

class CachePermissions extends Command
{
    protected $signature = 'roleperm:cache';

    protected $description = 'Rebuild the cached roles and permissions map';

    public function __construct(protected PermissionRegistry $registry)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->registry->flush();

        $count = count($this->registry->slugs());

        $this->info("Cached {$count} permissions.");

        return self::SUCCESS;
    }
}
