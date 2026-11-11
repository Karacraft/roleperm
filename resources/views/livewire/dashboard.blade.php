<div class="space-y-6">
    <section class="grid gap-4 sm:grid-cols-2">
        <div class="rounded-xl border border-slate-800 bg-slate-900/70 p-5">
            <p class="text-sm text-slate-400">{{ __('roles-and-permissions::ui.roles') }}</p>
            <p class="mt-2 text-3xl font-semibold">{{ number_format($roleCount) }}</p>
        </div>

        <div class="rounded-xl border border-slate-800 bg-slate-900/70 p-5">
            <p class="text-sm text-slate-400">{{ __('roles-and-permissions::ui.permissions') }}</p>
            <p class="mt-2 text-3xl font-semibold">{{ number_format($permissionCount) }}</p>
        </div>
    </section>

    <section class="rounded-xl border border-slate-800 bg-slate-900/70">
        <div class="flex flex-col gap-4 border-b border-slate-800 p-5 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex gap-2">
                <button type="button" wire:click="setTab('roles')"
                        class="rounded-md px-3 py-1.5 text-sm font-medium transition {{ $tab === 'roles' ? 'bg-indigo-500 text-white' : 'text-slate-400 hover:text-white' }}">
                    {{ __('roles-and-permissions::ui.roles') }}
                </button>
                <button type="button" wire:click="setTab('permissions')"
                        class="rounded-md px-3 py-1.5 text-sm font-medium transition {{ $tab === 'permissions' ? 'bg-indigo-500 text-white' : 'text-slate-400 hover:text-white' }}">
                    {{ __('roles-and-permissions::ui.permissions') }}
                </button>
            </div>

            <div class="relative w-full sm:max-w-xs">
                <input type="search"
                       wire:model.live.debounce.300ms="search"
                       placeholder="{{ __('roles-and-permissions::ui.search_placeholder') }}"
                       class="w-full rounded-md border border-slate-700 bg-slate-950 px-3 py-2 text-sm text-slate-100 placeholder-slate-500 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                <span wire:loading wire:target="search" class="absolute right-3 top-2 text-xs text-slate-500">...</span>
            </div>
        </div>

        @if ($tab === 'roles')
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-800 text-sm">
                    <thead class="bg-slate-900">
                        <tr>
                            <th class="px-5 py-3 text-left font-medium text-slate-400">{{ __('roles-and-permissions::ui.title_column') }}</th>
                            <th class="px-5 py-3 text-left font-medium text-slate-400">{{ __('roles-and-permissions::ui.slug_column') }}</th>
                            <th class="px-5 py-3 text-left font-medium text-slate-400">{{ __('roles-and-permissions::ui.guard_column') }}</th>
                            <th class="px-5 py-3 text-right font-medium text-slate-400">{{ __('roles-and-permissions::ui.permissions_column') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800">
                        @forelse ($roles as $role)
                            <tr wire:key="role-{{ $role->getKey() }}">
                                <td class="px-5 py-3 font-medium text-white">{{ $role->title }}</td>
                                <td class="px-5 py-3 text-slate-400">{{ $role->slug }}</td>
                                <td class="px-5 py-3 text-slate-400">{{ $role->guard_name }}</td>
                                <td class="px-5 py-3 text-right text-slate-300">{{ $role->permissions_count }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-10 text-center text-slate-500">{{ __('roles-and-permissions::ui.no_roles') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-800 px-5 py-3">
                {{ $roles->links() }}
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-800 text-sm">
                    <thead class="bg-slate-900">
                        <tr>
                            <th class="px-5 py-3 text-left font-medium text-slate-400">{{ __('roles-and-permissions::ui.title_column') }}</th>
                            <th class="px-5 py-3 text-left font-medium text-slate-400">{{ __('roles-and-permissions::ui.slug_column') }}</th>
                            <th class="px-5 py-3 text-left font-medium text-slate-400">{{ __('roles-and-permissions::ui.model_column') }}</th>
                            <th class="px-5 py-3 text-left font-medium text-slate-400">{{ __('roles-and-permissions::ui.method_column') }}</th>
                            <th class="px-5 py-3 text-left font-medium text-slate-400">{{ __('roles-and-permissions::ui.guard_column') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800">
                        @forelse ($permissions as $permission)
                            <tr wire:key="permission-{{ $permission->getKey() }}">
                                <td class="px-5 py-3 font-medium text-white">{{ $permission->title }}</td>
                                <td class="px-5 py-3 text-slate-400">{{ $permission->slug }}</td>
                                <td class="px-5 py-3 text-slate-400">{{ $permission->model ?? '-' }}</td>
                                <td class="px-5 py-3 text-slate-400">{{ $permission->method ?? '-' }}</td>
                                <td class="px-5 py-3 text-slate-400">{{ $permission->guard_name }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-10 text-center text-slate-500">{{ __('roles-and-permissions::ui.no_permissions') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-800 px-5 py-3">
                {{ $permissions->links() }}
            </div>
        @endif
    </section>
</div>
