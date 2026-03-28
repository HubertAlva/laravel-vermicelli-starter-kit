<?php

namespace App\Http\Responses;

use Inertia\Inertia;
use Laravel\Fortify\Contracts\LoginResponse;

class CustomLoginResponse implements LoginResponse
{
    public function toResponse($request)
    {
        if ($request->user()->isAdmin()) {
            return Inertia::location(route('admin.dashboard'));
        }

        return Inertia::location(route('dashboard'));
    }
}
