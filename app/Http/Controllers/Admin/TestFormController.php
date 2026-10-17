<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TestFormRequest;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Showcases every form component; nothing is persisted.
 */
class TestFormController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('admin/test-form/Page');
    }

    public function store(TestFormRequest $request): RedirectResponse
    {
        $this->toast('Formulario enviado correctamente.');

        return back();
    }
}
