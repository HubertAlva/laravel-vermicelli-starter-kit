<script lang="ts" setup>
import {
    ImageInput,
    MarkdownInput,
    SwitchInput,
    TagsInput,
    TextareaInput,
    TextInput,
} from '@/components/form/index';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { Field, FieldGroup, FieldSet } from '@/components/ui/field';
import { Spinner } from '@/components/ui/spinner';
import { validate } from '@/lib/utils';
import { InertiaPrecognitiveForm } from '@inertiajs/vue3';
import { ref, toRef } from 'vue';

const props = defineProps<{
    form: InertiaPrecognitiveForm<App.Data.FormPostData>;
    buttonLabel: string;
}>();

const form = toRef(props, 'form');

const currentThumbnailUrl = ref(form.value.thumbnail);

const validateField = validate(props.form);
</script>

<template>
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
                                @change="validateField('published_at')"
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
                            v-model:isNewImage="form.is_new_thumbnail"
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
                    <Button :disabled="form.processing" type="submit">
                        {{ buttonLabel }}
                    </Button>
                </Field>
            </FieldGroup>
        </CardContent>
    </Card>
</template>
