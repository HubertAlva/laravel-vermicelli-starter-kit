<script generic="T" lang="ts" setup>
import { Badge } from '@/components/ui/badge';
import { truncateText } from '@/lib/utils';
import { CellProps, Column } from '@/types/table';
import { computed } from 'vue';

interface Role {
    name: string;
}

interface Image {
    url: string;
}

const props = defineProps<CellProps<T>>();

const resolveValue = (row: T, field: Column<T>['field']): unknown => {
    if (typeof field === 'string' && field.includes('.')) {
        return field
            .split('.')
            .reduce<any>((value, key) => (value ? value[key] : null), row);
    }

    if (typeof field === 'string') {
        return (row as any)[field];
    }

    return row[field];
};

const value = computed(() => resolveValue(props.row, props.column.field));

const formattedDate = computed(() => {
    if (!value.value) return '';
    const date = new Date(value.value as string);
    return date.toLocaleDateString('es-PE');
});

const publishedLabel = computed(() => {
    return (props.row as any).published_at ? 'Publicado' : 'Borrador';
});

const formattedSize = computed(() => {
    if (props.column.type !== 'fileSize' || typeof value.value !== 'number') {
        return value.value;
    }

    const size = value.value;
    if (size < 1024) return `${size} B`;
    if (size < 1048576) return `${(size / 1024).toFixed(2)} KB`;
    if (size < 1073741824) return `${(size / 1048576).toFixed(2)} MB`;
    return `${(size / 1073741824).toFixed(2)} GB`;
});

const isPublished = (row: T): row is T & { published_at: string | null } =>
    typeof row === 'object' && row !== null && 'published_at' in row;

const hasImages = (row: T): row is T & { images: Image[] } =>
    typeof row === 'object' &&
    row !== null &&
    'images' in row &&
    Array.isArray((row as any).images);

const isRoleArray = (value: unknown): value is Role[] => {
    return (
        Array.isArray(value) &&
        value.length > 0 &&
        typeof value[0] === 'object' &&
        value[0] !== null &&
        'name' in value[0]
    );
};
</script>

<template>
    <div>
        <template v-if="column.field === 'name'">
            <span class="font-medium">
                {{ truncateText(value as string, 90) }}
            </span>
        </template>

        <template v-else-if="column.field === 'published_at'">
            <Badge
                v-if="isPublished(props.row)"
                :variant="props.row.published_at ? 'success' : 'warn'"
            >
                {{ publishedLabel }}
            </Badge>
        </template>

        <template
            v-else-if="
                column.field === 'created_at' ||
                column.field === 'updated_at' ||
                column.field === 'deleted_at'
            "
        >
            <span class="text-sm text-muted-foreground">
                {{ formattedDate }}
            </span>
        </template>

        <template v-else-if="column.type === 'image'">
            <img
                :src="value as string"
                alt="Thumbnail"
                class="h-10 w-10 rounded object-cover"
            />
        </template>

        <template v-else-if="column.type === 'images'">
            <img
                v-if="hasImages(props.row)"
                :src="props.row.images[0].url"
                alt="Thumbnail"
                class="h-10 w-10 rounded object-cover"
            />
        </template>

        <template v-else-if="column.field === 'roles'">
            <template v-if="isRoleArray(value)">
                <Badge class="capitalize">
                    {{ value[0].name }}
                </Badge>
            </template>
            <Badge v-else class="capitalize" variant="outline"> Usuario</Badge>
        </template>

        <template v-else-if="column.type === 'fileSize'">
            {{ formattedSize }}
        </template>

        <template v-else>
            {{ value }}
        </template>
    </div>
</template>
