<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Role\CreateRole;
use App\Actions\Role\UpdateRole;
use App\Data\PermissionData;
use App\Data\RoleData;
use App\Data\RoleFormData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RoleRequest;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class RoleController extends Controller
{
    public function index(): Response
    {
        Gate::authorize('viewAny', Role::class);

        return Inertia::render('admin/roles/index/Page', [
            'roles' => RoleData::collect(
                Role::query()->with('permissions')->withCount('users')->orderBy('name')->get(),
            ),
            'permissionGroups' => $this->permissionGroups(),
        ]);
    }

    public function create(): Response
    {
        Gate::authorize('create', Role::class);

        return Inertia::render('admin/roles/create/Page', [
            'permissionGroups' => $this->permissionGroups(),
        ]);
    }

    public function store(RoleRequest $request, CreateRole $createRole): RedirectResponse
    {
        $role = $createRole->execute(RoleFormData::from($request->validated()));

        $this->toast('Rol creado correctamente.', id: $role->id);

        return to_route('admin.roles.index');
    }

    public function edit(Role $role): Response
    {
        Gate::authorize('update', $role);

        return Inertia::render('admin/roles/edit/Page', [
            'role' => RoleData::from($role->load('permissions')->loadCount('users')),
            'permissionGroups' => $this->permissionGroups(),
        ]);
    }

    public function update(RoleRequest $request, Role $role, UpdateRole $updateRole): RedirectResponse
    {
        $updateRole->execute($role, RoleFormData::from($request->validated()));

        $this->toast('Rol actualizado correctamente.', id: $role->id);

        return back();
    }

    /**
     * Group the permissions by the `resource` prefix of their name.
     *
     * @return array<int, array{resource: string, permissions: array<int, PermissionData>}>
     */
    protected function permissionGroups(): array
    {
        return Permission::query()
            ->orderBy('name')
            ->get()
            ->groupBy(fn (Permission $permission): string => str($permission->name)->before('.')->toString())
            ->sortKeys()
            ->map(fn ($permissions, string $resource): array => [
                'resource' => $resource,
                'permissions' => PermissionData::collect($permissions->values())->all(),
            ])
            ->values()
            ->all();
    }
}
