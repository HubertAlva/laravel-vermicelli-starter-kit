<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Laravel\Fortify\Contracts\TwoFactorLoginResponse as TwoFactorLoginResponseContract;
use Symfony\Component\HttpFoundation\Response;

class LoginResponse implements LoginResponseContract, TwoFactorLoginResponseContract
{
    /**
     * Redirect users to the intended URL, or to the admin panel / user dashboard.
     *
     * A full page visit is forced because the admin panel lives on another
     * subdomain, which an Inertia XHR redirect cannot follow.
     *
     * @param  Request  $request
     */
    public function toResponse($request): Response
    {
        if ($request->wantsJson()) {
            return new JsonResponse(['two_factor' => false]);
        }

        $home = $request->user()?->canAccessAdminPanel()
            ? route('admin.dashboard')
            : route('dashboard');

        return Inertia::location(redirect()->intended($home)->getTargetUrl());
    }
}
