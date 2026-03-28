import { onMounted, onUnmounted } from 'vue';
import { usePage } from '@inertiajs/vue3';

export function useUnsavedChanges(isDirty: () => boolean) {
    if (usePage().props.isLocal) return;

    const handler = (event: BeforeUnloadEvent) => {
        if (!isDirty()) return;

        event.preventDefault();
        event.returnValue = '';
    };

    onMounted(() => {
        window.addEventListener('beforeunload', handler);
    });

    onUnmounted(() => {
        window.removeEventListener('beforeunload', handler);
    });
}
