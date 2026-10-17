/**
 * Props shared by every form input. Inputs emit `validate` whenever their
 * value changes so pages can trigger precognitive validation.
 */
export type BaseInputProps = {
    id?: string;
    label?: string;
    description?: string;
    error?: string;
};

/**
 * A gallery item: a new upload, or an existing image that can be flagged
 * for deletion (and restored) before the form is submitted.
 */
export type GalleryItem =
    | { file: File; url?: never; deleted?: never }
    | { url: string; file?: never; deleted: boolean };
