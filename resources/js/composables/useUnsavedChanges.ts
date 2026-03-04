import { onMounted, onUnmounted } from 'vue';

export function useUnsavedChanges(isDirty: () => boolean) {
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
