<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CleanupEditorTempImagesCommand extends Command
{
    protected $signature = 'editor:cleanup-temp-images';

    protected $description = 'Delete unused temporary editor images';

    public function handle(): void
    {
        $files = Storage::disk('editor_tmp')->allFiles();

        foreach ($files as $file) {
            $lastModified = Storage::disk('editor_tmp')->lastModified($file);

            if ($lastModified < now()->subHours(2)->timestamp) {
                Storage::disk('editor_tmp')->delete($file);
                $this->info('Deleted temporary image: ' . $file);
            }
        }
    }
}
