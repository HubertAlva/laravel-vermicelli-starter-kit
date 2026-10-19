<?php

namespace App\Http\Controllers\Admin;

use App\Data\PermissionData;
use App\Data\PermissionFormData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PermissionRequest;
use App\Models\Permission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Manages custom permissions. System permissions (checked by the code) are read-only.
 */
class PermissionController extends Controller
{
    public function create(): Response
    {
        Gate::authorize('create', Permission::class);

        return Inertia::render('admin/permissions/create/Page');
    }

    public function store(PermissionRequest $request): RedirectResponse
    {
        $data = PermissionFormData::from($request->validated());

        $permission = Permission::query()->create(['name' => $data->name]);

        $this->toast('Permiso creado correctamente.', id: $permission->id);

        return to_route('admin.roles.index');
    }

    public function edit(Permission $permission): Response
    {
        Gate::authorize('update', $permission);

        return Inertia::render('admin/permissions/edit/Page', [
            'permission' => PermissionData::from($permission),
            'roles' => $permission->roles()->orderBy('name')->pluck('name'),
            'canDelete' => Gate::allows('delete', $permission),
        ]);
    }

    public function update(PermissionRequest $request, Permission $permission): RedirectResponse
    {
        $data = PermissionFormData::from($request->validated());

        $permission->update(['name' => $data->name]);

        $this->toast('Permiso actualizado correctamente.', id: $permission->id);

        return back();
    }

    public function destroy(Permission $permission): RedirectResponse
    {
        if (Gate::denies('delete', $permission)) {
            $this->toast('No puedes eliminar este permiso.', 'error', $permission->id);

            return back();
        }

        $permission->delete();

        $this->toast('Permiso eliminado correctamente.', id: $permission->id);

        return to_route('admin.roles.index');
    }
}
