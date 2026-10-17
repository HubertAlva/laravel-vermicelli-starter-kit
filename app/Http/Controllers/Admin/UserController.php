<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Admin\GetIndexData;
use App\Actions\User\CreateUser;
use App\Actions\User\UpdateUser;
use App\Data\UserData;
use App\Data\UserFormData;
use App\Filters\SearchFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\LaravelData\PaginatedDataCollection;
use Spatie\Permission\Models\Role;
use Spatie\QueryBuilder\AllowedFilter;

class UserController extends Controller
{
    public function index(Request $request, GetIndexData $indexData): Response
    {
        Gate::authorize('viewAny', User::class);

        return Inertia::render('admin/users/index/Page', [
            'users' => Inertia::defer(fn () => UserData::collect(
                $indexData->execute(
                    model: User::class,
                    with: ['roles', 'lock'],
                    allowedFilters: [
                        AllowedFilter::custom('search', new SearchFilter(['name', 'email'])),
                        AllowedFilter::callback('role', fn (Builder $query, string $role) => $query->whereRelation('roles', 'name', $role)),
                    ],
                ),
                PaginatedDataCollection::class,
            )),
            'filters' => [
                'search' => $request->input('filter.search'),
                'role' => $request->input('filter.role'),
                'per_page' => $indexData->perPage(),
            ],
            'roles' => $this->roles(),
        ]);
    }

    public function create(): Response
    {
        Gate::authorize('create', User::class);

        return Inertia::render('admin/users/create/Page', [
            'roles' => $this->roles(),
        ]);
    }

    public function store(UserRequest $request, CreateUser $createUser): RedirectResponse
    {
        $user = $createUser->execute(UserFormData::from($request->validated()));

        $this->toast('Usuario creado correctamente.', id: $user->id);

        return to_route('admin.users.index');
    }

    public function edit(User $user): Response
    {
        Gate::authorize('update', $user);

        return Inertia::render('admin/users/edit/Page', [
            'user' => UserData::from($user),
            'roles' => $this->roles(),
            'canDelete' => Gate::allows('delete', $user),
        ]);
    }

    public function update(UserRequest $request, User $user, UpdateUser $updateUser): RedirectResponse
    {
        $updateUser->execute($user, UserFormData::from($request->validated()));

        $this->toast('Usuario actualizado correctamente.', id: $user->id);

        return back();
    }

    public function destroy(User $user): RedirectResponse
    {
        if (Gate::denies('delete', $user)) {
            $this->toast('No puedes eliminar este usuario.', 'error', $user->id);

            return back();
        }

        $user->delete();

        $this->toast('Usuario eliminado correctamente.', id: $user->id);

        return $this->backToIndex('admin.users.index');
    }

    /**
     * Get the assignable roles as select options.
     *
     * @return array<int, array{value: string, label: string}>
     */
    protected function roles(): array
    {
        return Role::query()
            ->orderBy('name')
            ->pluck('name')
            ->map(fn (string $name): array => ['value' => $name, 'label' => str($name)->headline()->toString()])
            ->values()
            ->all();
    }
}
