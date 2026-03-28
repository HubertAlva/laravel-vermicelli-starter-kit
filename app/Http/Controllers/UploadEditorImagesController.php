<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadEditorImagesController extends Controller
{
    public function __invoke(Request $request)
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
