<script lang="ts" setup>
import {
    ImageInput,
    MarkdownInput,
    SwitchInput,
    TagsInput,
    TextareaInput,
    TextInput,
} from '@/components/form';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { Field, FieldGroup, FieldSet } from '@/components/ui/field';
import { Spinner } from '@/components/ui/spinner';
import AdminLayout from '@/layouts/AdminLayout.vue';
import { cn, truncateText, validate } from '@/lib/utils';
import posts from '@/routes/admin/posts';
import { useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const title = 'Crear artículo';

const breadcrumb = [
    {
        name: title,
    },
];
const form = useForm({
    name: undefined,
    excerpt: undefined,
    content: undefined,
    thumbnail: null,
    published_at: true,
    is_new_thumbnail: false,
    delete_at: null,
    tags: undefined,
}).withPrecognition('post', posts.store().url);

const submit = () => form.submit();

form.validateFiles();

const validateField = validate(form);

const publishLabel = computed(() => {
    return form.published_at ? 'Publicar' : 'Borrador';
});

window.addEventListener('beforeunload', (event) => {
    if (form.isDirty) {
        event.preventDefault();
        event.returnValue = '';
    }
});

const currentThumbnailUrl = ref(form.thumbnail);
</script>

<template>
    <AdminLayout :breadcrumb="breadcrumb" :title="title" container="full">
        <div>
            <div
                class="sticky top-0 z-10 -mt-4 flex flex-wrap items-center justify-between gap-4 bg-muted py-4"
            >
                <div class="flex flex-wrap items-center justify-start gap-2">
                    <div class="flex items-center justify-start gap-2">
                        <h1 class="text-2xl font-semibold">
                            {{
                                truncateText(
                                    form.name ? form.name : 'Nuevo artículo',
                                    35,
                                )
                            }}
                        </h1>
                    </div>

                    <Badge :variant="form.published_at ? 'success' : 'warn'">
                        {{ publishLabel }}
                    </Badge>
                </div>

                <div>
                    <Button type="submit" @click="submit">Crear</Button>
                </div>
            </div>

            <form
                :class="
                    cn(
                        'grid gap-4 md:grid-cols-2',
                        form.processing ? 'pointer-events-none opacity-50' : '',
                    )
                "
                @submit.prevent="submit"
            >
                <Card>
                    <CardContent>
                        <FieldGroup>
                            <FieldSet>
                                <FieldGroup>
                                    <div class="rounded-md border p-4">
                                        <SwitchInput
                                            id="published_at"
                                            v-model="form.published_at"
                                            :error="form.errors.published_at"
                                            description="Si creas el artículo sin publicarlo no se mostrará en la sección de blog."
                                            label="¿Publicar artículo?"
                                            @change="
                                                validateField('published_at')
                                            "
                                        />
                                    </div>

                                    <TextInput
                                        id="name"
                                        v-model="form.name"
                                        :error="form.errors.name"
                                        label="Nombre"
                                        placeholder="Nuevo post"
                                        type="text"
                                        @change="validateField('name')"
                                    />

                                    <TextareaInput
                                        id="excerpt"
                                        v-model="form.excerpt"
                                        :error="form.errors.excerpt"
                                        label="Extracto"
                                        placeholder="Tu contenido"
                                        @change="validateField('excerpt')"
                                    />
                                </FieldGroup>
                            </FieldSet>
                        </FieldGroup>
                    </CardContent>
                </Card>

                <Card>
                    <CardContent>
                        <FieldGroup>
                            <FieldSet>
                                <FieldGroup>
                                    <ImageInput
                                        v-model="form.thumbnail"
                                        v-model:isNewImage="
                                            form.is_new_thumbnail
                                        "
                                        :currentImageUrl="currentThumbnailUrl"
                                        :error="form.errors.thumbnail"
                                        description="PNG, JPG, WEBP (máx. 20 MB)"
                                        label="Thumbnail"
                                        @validate="validateField('thumbnail')"
                                    />

                                    <TagsInput
                                        v-model="form.tags"
                                        :error="form.errors.tags"
                                        :max="10"
                                        label="Etiquetas"
                                        @validate="validateField('tags')"
                                    />
                                </FieldGroup>
                            </FieldSet>
                        </FieldGroup>
                    </CardContent>
                </Card>

                <Card class="md:col-span-2">
                    <CardContent>
                        <FieldGroup>
                            <FieldSet>
                                <FieldGroup>
                                    <MarkdownInput
                                        v-model="form.content"
                                        :error="form.errors.content"
                                        label="Contenido"
                                        @validate="validateField('content')"
                                    />
                                </FieldGroup>
                            </FieldSet>

                            <Field orientation="horizontal">
                                <Spinner v-if="form.validating" />
                                <Button
                                    :disabled="form.processing"
                                    type="submit"
                                >
                                    Crear
                                </Button>
                            </Field>
                        </FieldGroup>
                    </CardContent>
                </Card>
            </form>
        </div>
    </AdminLayout>
</template>
