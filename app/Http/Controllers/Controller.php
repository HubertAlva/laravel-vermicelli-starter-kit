<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;

abstract class Controller
{
    /**
     * Flash a toast notification to be shown on the next page.
     *
     * @param  'success'|'info'|'warning'|'error'  $type
     */
    protected function toast(string $message, string $type = 'success', ?int $id = null): void
    {
        Inertia::flash('toast', array_filter([
            'type' => $type,
            'message' => $message,
            'id' => $id,
        ], fn (mixed $value): bool => $value !== null));
    }

    /**
     * Go back when the request came from the given index page (keeping its
     * page and filters), otherwise redirect to it.
     */
    protected function backToIndex(string $indexRoute): RedirectResponse
    {
        $previousPath = parse_url(url()->previous(), PHP_URL_PATH) ?: '/';
        $indexPath = parse_url(route($indexRoute), PHP_URL_PATH) ?: '/';

        return $previousPath === $indexPath ? back() : to_route($indexRoute);
    }
}
