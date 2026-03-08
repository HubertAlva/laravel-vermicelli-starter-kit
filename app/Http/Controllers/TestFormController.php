<?php

namespace App\Http\Controllers;

use App\Http\Requests\TestFormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class TestFormController extends Controller
{
    public function index(): Response
    {
        return inertia('admin/test-form/Page');
    }

    public function store(TestFormRequest $request): RedirectResponse
    {
        Inertia::flash('toast',
            [
                'type' => 'success',
                'message' => 'Formulario enviado correctamente.',
            ]);

        return Redirect::back();
    }
}
