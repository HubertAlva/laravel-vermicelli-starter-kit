<?php

namespace App\Http\Controllers;

use App\Actions\SendContactMessage;
use App\Data\ContactData;
use App\Http\Requests\ContactRequest;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ContactController extends Controller
{
    public function show(): Response
    {
        return Inertia::render('contact/index/Page');
    }

    public function store(ContactRequest $request, SendContactMessage $sendContactMessage): RedirectResponse
    {
        $sendContactMessage->execute(ContactData::from($request->validated()));

        $this->toast('Mensaje enviado correctamente.');

        return back();
    }
}
