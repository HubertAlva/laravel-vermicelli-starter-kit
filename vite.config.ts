import inertia from '@inertiajs/vite';
import { wayfinder } from '@laravel/vite-plugin-wayfinder';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import { defineConfig, lazyPlugins, loadEnv } from 'vite-plus';

// Empty prefix: also read APP_DOMAIN/APP_URL (used here only, never exposed)...
const env = loadEnv(process.env.NODE_ENV ?? 'development', process.cwd(), '');

/**
 * When set, the dev server binds to this local domain (e.g. `yourdomain.local`)
 * so HMR works on both the site and the admin subdomain.
 */
const appDomain = env.VITE_APP_DOMAIN || undefined;

/**
 * Pages on the domain and any subdomain (e.g. `admin.`) may load dev assets.
 * Without this, the browser blocks the scripts on the admin subdomain.
 */
const corsDomain =
    appDomain ||
    env.APP_DOMAIN ||
    (env.APP_URL ? new URL(env.APP_URL).hostname : undefined);
const appDomainPattern = corsDomain?.replaceAll('.', '\\.');

export default defineConfig({
    plugins: lazyPlugins(() => [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.ts'],
            refresh: true,
            fonts: [
                bunny('Instrument Sans', {
                    weights: [400, 500, 600],
                }),
            ],
        }),
        inertia(),
        tailwindcss(),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        wayfinder({
            formVariants: true,
        }),
    ]),
    server: {
        host: appDomain,
        port: 5173,
        strictPort: true,
        hmr: appDomain ? { host: appDomain, protocol: 'ws' } : undefined,
        cors: appDomainPattern
            ? {
                  origin: new RegExp(
                      `^https?://([a-z0-9-]+\\.)*${appDomainPattern}(:\\d+)?$`,
                  ),
              }
            : undefined,
        watch: {
            ignored: [
                '**/.agents/**',
                '**/.claude/**',
                '**/.cursor/**',
                '**/.junie/**',
                '**/vendor/**',
            ],
        },
    },
    lint: {
        ignorePatterns: [
            'vendor/**',
            'node_modules/**',
            'public/**',
            'bootstrap/ssr/**',
            'tailwind.config.js',
            'resources/js/actions/**',
            'resources/js/components/ui/*',
            'resources/js/routes/**',
            'resources/js/wayfinder/**',
            'resources/js/types/generated.d.ts',
        ],
        options: {
            denyWarnings: true,
            typeAware: true,
        },
    },
    fmt: {
        printWidth: 80,
        tabWidth: 4,
        singleQuote: true,
        semi: true,
        singleAttributePerLine: false,
        htmlWhitespaceSensitivity: 'css',
        ignorePatterns: [
            '.github/**',
            'composer.json',
            'resources/js/components/ui/*',
            'resources/js/types/generated.d.ts',
            'resources/views/mail/*',
        ],
        sortTailwindcss: {
            functions: ['clsx', 'cn', 'cva'],
            stylesheet: 'resources/css/app.css',
        },
    },
});
