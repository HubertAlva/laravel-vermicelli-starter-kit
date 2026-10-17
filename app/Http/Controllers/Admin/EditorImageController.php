<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\EditorContentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EditorImageController extends Controller
{
    /**
     * Store an image uploaded from the Markdown editor in temporary storage.
     *
     * It is moved into the model's media collection when the form is saved,
     * and removed by `editor:cleanup-images` otherwise.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $request->validate([
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:10240'],
        ]);

        $disk = Storage::disk(EditorContentService::TEMP_DISK);
        $path = $request->file('image')->store('', EditorContentService::TEMP_DISK);

        return response()->json([
            'url' => $disk->url((string) $path),
        ]);
    }
}
