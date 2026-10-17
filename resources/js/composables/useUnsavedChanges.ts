import { usePage } from '@inertiajs/vue3';
import { onMounted, onUnmounted } from 'vue';

/**
 * Warn the user before leaving the page while a form has unsaved changes.
 * Disabled in the local environment to keep development friction-free.
 */
export function useUnsavedChanges(isDirty: () => boolean): void {
    if (usePage().props.isLocal) {
        return;
    }

    const handler = (event: BeforeUnloadEvent) => {
        if (!isDirty()) {
            return;
        }

        event.preventDefault();
    };

    onMounted(() => window.addEventListener('beforeunload', handler));
    onUnmounted(() => window.removeEventListener('beforeunload', handler));
}
