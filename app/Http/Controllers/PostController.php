<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class PostController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('admin/posts/index/Page');
    }

    public function create(): Response
    {
        return Inertia::render('admin/posts/create/Page');
    }

    public function store(StorePostRequest $request, Post $post): RedirectResponse
    {
        $validated = $request->validated();

        dd($validated);

        return Redirect::route('admin.posts.index')->with('success', 'Artículo creado correctamente.');
    }
}
