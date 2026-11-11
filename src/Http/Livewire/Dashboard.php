<?php

namespace Karacraft\RolesAndPermissions\Http\Livewire;

use Illuminate\Contracts\View\View;
use Karacraft\RolesAndPermissions\Support\Config;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('roles-and-permissions::layouts.app')]
class Dashboard extends Component
{
    use WithPagination;

    #[Url(as: 'q', history: true)]
    public string $search = '';

    public string $tab = 'roles';

    public function mount(): void
    {
        $permission = config('roles-and-permissions.ui.permission');

        if (is_string($permission) && trim($permission) !== '') {
            abort_unless(auth()->check() && auth()->user()->can($permission), 403);
        }
    }

    public function updatedSearch(): void
    {
        $this->resetPage('rolesPage');
        $this->resetPage('permissionsPage');
    }

    public function setTab(string $tab): void
    {
        $this->tab = in_array($tab, ['roles', 'permissions'], true) ? $tab : 'roles';
    }

    public function render(): View
    {
        $perPage = (int) config('roles-and-permissions.ui.per_page', 15);
        $search = trim($this->search);

        $roleModel = Config::roleModel();
        $permissionModel = Config::permissionModel();

        $roles = $roleModel::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%");
                });
            })
            ->withCount('permissions')
            ->orderBy('title')
            ->paginate($perPage, ['*'], 'rolesPage');

        $permissions = $permissionModel::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%")
                        ->orWhere('model', 'like', "%{$search}%");
                });
            })
            ->orderBy('model')
            ->orderBy('title')
            ->paginate($perPage, ['*'], 'permissionsPage');

        return view('roles-and-permissions::livewire.dashboard', [
            'roles' => $roles,
            'permissions' => $permissions,
            'roleCount' => $roleModel::query()->count(),
            'permissionCount' => $permissionModel::query()->count(),
        ]);
    }
}
