<?php

namespace App\Http\Controllers;

use App\Actions\Admin\GetIndexData;
use App\Actions\User\Create;
use App\Actions\User\Update;
use App\Data\UserData;
use App\Data\UserFormData;
use App\Filters\SearchFilter;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\LaravelData\PaginatedDataCollection;
use Spatie\QueryBuilder\AllowedFilter;

class UserController extends Controller
{
    public function index(GetIndexData $action): Response
    {
        Gate::authorize('viewAny', User::class);

        $users = $action->execute(
            model: User::class,
            with: ['roles'],
            allowedFilters: [
                AllowedFilter::custom('search', new SearchFilter(['name', 'email'])),
            ],
        );

        return Inertia::render('admin/users/index/Page', [
            'users' => Inertia::defer(fn () => UserData::collect($users, PaginatedDataCollection::class)->wrap('data')),
            'filters' => [
                'search' => request('filter.search'),
                'per_page' => request()->integer('per_page', 10),
            ],
        ]);
    }

    public function create(): Response
    {
        Gate::authorize('create', User::class);

        return Inertia::render('admin/users/create/Page');
    }

    public function store(StoreUserRequest $request, Create $action): RedirectResponse
    {
        Gate::authorize('create', User::class);

        $action->execute(
            UserFormData::from($request->validated()),
        );

        Inertia::flash('toast',
            [
                'type' => 'success',
                'message' => 'Usuario creado correctamente.',
            ]);

        return Redirect::route('admin.users.index');
    }

    public function edit(User $user): Response
    {
        Gate::authorize('update', $user);

        return Inertia::render('admin/users/edit/Page', [
            'user' => UserData::from($user),
        ]);
    }

    public function update(UpdateUserRequest $request, Update $action, User $user): RedirectResponse
    {
        Gate::authorize('update', $user);

        $user = $action->execute(
            $user,
            UserFormData::from($request->validated()),
        );

        Inertia::flash('toast',
            [
                'type' => 'success',
                'message' => 'Usuario actualizado correctamente.',
                'id' => $user->id,
            ]);

        $user->refresh();

        return Redirect::back();
    }

    public function destroy(User $user): RedirectResponse
    {
        if (Gate::denies('delete', $user)) {
            Inertia::flash('toast',
                [
                    'type' => 'error',
                    'message' => 'No tienes permisos para eliminar este usuario.',
                    'id' => $user->id,
                ]);

            return Redirect::back();
        }

        $user->delete();

        $currentPage = request('currentPage');

        Inertia::flash('toast',
            [
                'type' => 'success',
                'message' => 'Usuario eliminado correctamente.',
                'id' => $user->id,
            ]);

        return Redirect::route('admin.users.index', [
            'page' => $currentPage,
        ]);
    }
}
