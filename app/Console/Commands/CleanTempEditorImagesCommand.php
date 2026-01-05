<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CleanTempEditorImagesCommand extends Command
{
    protected $signature = 'app:clean-temp-editor-images';

    protected $description = 'Delete unused temporary editor images';

    public function handle(): void
    {
        $disk = Storage::disk('editor_tmp');

        $files = $disk->allFiles();

        foreach ($files as $file) {
            $lastModified = $disk->lastModified($file);

            if ($lastModified < now()->subHours(24)->timestamp) {
                $disk->delete($file);
            }
        }

        $this->info('Temporary editor images cleaned.');
    }
}
