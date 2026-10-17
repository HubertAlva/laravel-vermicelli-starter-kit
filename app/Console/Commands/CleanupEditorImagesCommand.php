<?php

namespace App\Console\Commands;

use App\Services\EditorContentService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

#[Signature('editor:cleanup-images {--hours=2 : Delete images older than this many hours}')]
#[Description('Delete temporary editor images that were never attached to a model')]
class CleanupEditorImagesCommand extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $disk = Storage::disk(EditorContentService::TEMP_DISK);
        $threshold = now()->subHours((int) $this->option('hours'))->getTimestamp();
        $deleted = 0;

        foreach ($disk->allFiles() as $file) {
            if ($disk->lastModified($file) < $threshold) {
                $disk->delete($file);
                $deleted++;
            }
        }

        $this->components->info("Deleted {$deleted} temporary image(s).");

        return self::SUCCESS;
    }
}
