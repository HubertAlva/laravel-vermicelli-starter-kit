import type { Ref } from 'vue';
import { ref } from 'vue';

export type UseFileDropOptions = {
    /** Accepted MIME types, e.g. `['image/png']`. Accepts everything when empty. */
    accept?: string[];
    /** Maximum size of each file, in bytes. */
    maxSize?: number;
    onFiles: (files: File[]) => void;
};

export type UseFileDropReturn = {
    inputRef: Ref<HTMLInputElement | null>;
    isDragging: Ref<boolean>;
    openFilePicker: () => void;
    clearInput: () => void;
    onInputChange: (event: Event) => void;
    dropzoneListeners: {
        dragover: (event: DragEvent) => void;
        dragleave: (event: DragEvent) => void;
        drop: (event: DragEvent) => void;
    };
};

/**
 * Shared click-to-pick and drag-and-drop handling for file inputs.
 */
export function useFileDrop(options: UseFileDropOptions): UseFileDropReturn {
    const inputRef = ref<HTMLInputElement | null>(null);
    const isDragging = ref(false);

    function filter(files: File[]): File[] {
        return files.filter(
            (file) =>
                (!options.accept?.length ||
                    options.accept.includes(file.type)) &&
                (!options.maxSize || file.size <= options.maxSize),
        );
    }

    function handle(fileList: FileList | null | undefined): void {
        const files = filter(Array.from(fileList ?? []));

        if (files.length) {
            options.onFiles(files);
        }
    }

    function clearInput(): void {
        if (inputRef.value) {
            inputRef.value.value = '';
        }
    }

    return {
        inputRef,
        isDragging,
        openFilePicker: () => inputRef.value?.click(),
        clearInput,
        onInputChange: (event: Event) => {
            handle((event.target as HTMLInputElement).files);
            clearInput();
        },
        dropzoneListeners: {
            dragover: (event: DragEvent) => {
                event.preventDefault();
                isDragging.value = true;
            },
            dragleave: (event: DragEvent) => {
                event.preventDefault();
                isDragging.value = false;
            },
            drop: (event: DragEvent) => {
                event.preventDefault();
                isDragging.value = false;
                handle(event.dataTransfer?.files);
            },
        },
    };
}
