<?php

namespace Karacraft\RolesAndPermissions\Support;

use Illuminate\Contracts\Cache\Repository;

class PermissionRegistry
{
    /**
     * @var array<int, string>|null
     */
    protected ?array $slugs = null;

    public function __construct(
        protected Repository $cache,
        protected bool $enabled = true,
        protected string $cacheKey = 'roles-and-permissions.permissions',
        protected ?int $ttl = null,
    ) {
    }

    /**
     * @return array<int, string>
     */
    public function slugs(): array
    {
        if ($this->slugs !== null) {
            return $this->slugs;
        }

        if (! $this->enabled) {
            return $this->slugs = $this->load() ?? [];
        }

        $cached = $this->cache->get($this->cacheKey);

        if (is_array($cached)) {
            return $this->slugs = $cached;
        }

        $slugs = $this->load();

        if ($slugs === null) {
            return [];
        }

        $this->cache->put($this->cacheKey, $slugs, $this->ttl);

        return $this->slugs = $slugs;
    }

    public function contains(string $ability): bool
    {
        return in_array($ability, $this->slugs(), true);
    }

    public function flush(): void
    {
        $this->slugs = null;
        $this->cache->forget($this->cacheKey);
    }

    /**
     * @return array<int, string>|null
     */
    protected function load(): ?array
    {
        try {
            $permissionModel = Config::permissionModel();

            return $permissionModel::query()
                ->pluck('slug')
                ->filter()
                ->unique()
                ->values()
                ->all();
        } catch (\Throwable $e) {
            report($e);

            return null;
        }
    }
}
