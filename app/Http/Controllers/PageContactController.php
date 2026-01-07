<?php

namespace App\Http\Controllers;

use App\Actions\SentContact;
use App\Data\SendContactData;
use App\Http\Requests\SendContactRequest;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

class PageContactController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('contact/index/Page');
    }

    public function store(SendContactRequest $request, SentContact $action): RedirectResponse
    {
        $action->execute(
            SendContactData::from($request->validated())
        );

        return Inertia::flash('toast',
            [
                'type' => 'success',
                'message' => 'Mensaje enviado correctamente.',
            ])->back();
    }
}
