import { AppPageProps, Toast } from '@/types/index';

// Extend ImportMeta interface for Vite...
declare module 'vite/client' {
    interface ImportMetaEnv {
        readonly VITE_APP_NAME: string;
        [key: string]: string | boolean | undefined;
    }

    interface ImportMeta {
        readonly env: ImportMetaEnv;
        readonly glob: <T>(pattern: string) => Record<string, () => Promise<T>>;
    }
}

declare module '@inertiajs/core' {
    interface PageProps extends InertiaPageProps, AppPageProps {}

    export type PaginatorMeta = {
        current_page: number;
    };

    export type PaginatedProp<T = unknown> = {
        data: T[];
        meta: PaginatorMeta;
    };

    export interface InertiaConfig {
        flashDataType: {
            toast?: Toast;
        };

        // This may cause problems
        sharedPageProps: {
            [key: string]: PaginatedProp;
        };
    }
}

declare module 'vue' {
    interface ComponentCustomProperties {
        $inertia: typeof Router;
        $page: Page;
        $headManager: ReturnType<typeof createHeadManager>;
    }
}
