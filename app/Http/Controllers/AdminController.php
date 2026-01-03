<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class AdminController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('admin/dashboard/Page');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'image' => ['required', 'image', 'max:10240'],
        ]);

        $path = $request->file('image')->store('/', 'editor_tmp');

        return response()->json([
            'url' => Storage::disk('editor_tmp')->url($path),
            'path' => $path,
        ]);
    }
}
