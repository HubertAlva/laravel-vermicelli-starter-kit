import { Toast } from '@/types';
import { toast } from 'vue-sonner';

export function useToast() {
    function show({ type, title, message }: Toast) {
        const resolvedTitle =
            (title ?? (type === 'success' && 'Éxito')) ||
            (type === 'info' && 'Info') ||
            (type === 'warning' && 'Advertencia') ||
            (type === 'error' && 'Error') ||
            'Nuevo mensaje';

        toast[type](resolvedTitle, {
            description: message,
        });
    }
    return { show };
}
