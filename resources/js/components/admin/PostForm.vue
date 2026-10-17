<script setup lang="ts">
import type { InertiaPrecognitiveForm } from '@inertiajs/vue3';
import {
    ImageInput,
    MarkdownInput,
    SwitchInput,
    TagsInput,
    TextareaInput,
    TextInput,
} from '@/components/form';
import { Card, CardContent } from '@/components/ui/card';
import { FieldGroup } from '@/components/ui/field';

/**
 * Fields shared by the create and edit post pages.
 */
const { form } = defineProps<{
    form: InertiaPrecognitiveForm<App.Data.PostFormData>;
}>();
</script>

<template>
    <div class="grid gap-4 md:grid-cols-2">
        <Card>
            <CardContent>
                <FieldGroup>
                    <div class="rounded-md border p-4">
                        <SwitchInput
                            id="published"
                            v-model="form.published"
                            label="¿Publicar artículo?"
                            description="Los borradores no se muestran en el blog."
                            :error="form.errors.published"
                        />
                    </div>

                    <TextInput
                        id="name"
                        v-model="form.name"
                        label="Nombre"
                        placeholder="Nuevo artículo"
                        :error="form.errors.name"
                        @validate="form.validate('name')"
                    />

                    <TextareaInput
                        id="excerpt"
                        v-model="form.excerpt"
                        label="Extracto"
                        placeholder="Resumen del artículo"
                        :error="form.errors.excerpt"
                        @validate="form.validate('excerpt')"
                    />
                </FieldGroup>
            </CardContent>
        </Card>

        <Card>
            <CardContent>
                <FieldGroup>
                    <ImageInput
                        id="thumbnail"
                        v-model="form.thumbnail"
                        v-model:is-new-image="form.is_new_thumbnail"
                        label="Miniatura"
                        description="PNG, JPG o WEBP (máx. 10 MB)"
                        :error="form.errors.thumbnail"
                        @validate="form.validate('thumbnail')"
                    />

                    <TagsInput
                        id="tags"
                        v-model="form.tags"
                        label="Etiquetas"
                        placeholder="Escribe y presiona Enter"
                        :max="10"
                        :error="form.errors.tags"
                        @validate="form.validate('tags')"
                    />
                </FieldGroup>
            </CardContent>
        </Card>

        <Card class="md:col-span-2">
            <CardContent>
                <MarkdownInput
                    id="content"
                    v-model="form.content"
                    label="Contenido"
                    :error="form.errors.content"
                    @validate="form.validate('content')"
                />
            </CardContent>
        </Card>
    </div>
</template>
