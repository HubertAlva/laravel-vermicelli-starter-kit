<?php

namespace App\Providers;

use Illuminate\Http\UploadedFile;
use Spatie\LaravelTypeScriptTransformer\LaravelData\LaravelDataTypeScriptTransformerExtension;
use Spatie\LaravelTypeScriptTransformer\TypeScriptTransformerApplicationServiceProvider as BaseTypeScriptTransformerServiceProvider;
use Spatie\TypeScriptTransformer\Transformers\AttributedClassTransformer;
use Spatie\TypeScriptTransformer\Transformers\EnumTransformer;
use Spatie\TypeScriptTransformer\TypeScriptNodes\TypeScriptRaw;
use Spatie\TypeScriptTransformer\TypeScriptTransformerConfigFactory;
use Spatie\TypeScriptTransformer\Writers\GlobalNamespaceWriter;

class TypeScriptTransformerServiceProvider extends BaseTypeScriptTransformerServiceProvider
{
    /**
     * Configure how PHP classes are transformed into TypeScript.
     *
     * Run `php artisan typescript:transform` to regenerate `resources/js/types/generated.d.ts`.
     */
    protected function configure(TypeScriptTransformerConfigFactory $config): void
    {
        $config
            ->outputDirectory(resource_path('js/types'))
            ->withoutManifest()
            ->extension(new LaravelDataTypeScriptTransformerExtension)
            ->transformer(AttributedClassTransformer::class)
            ->transformer(EnumTransformer::class)
            ->replaceType(UploadedFile::class, new TypeScriptRaw('File'))
            ->transformDirectories(app_path())
            ->writer(new GlobalNamespaceWriter('generated.d.ts'));
    }
}
