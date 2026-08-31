<script lang="ts" setup>
import {
    CheckboxInput,
    ComboboxInput,
    DateInput,
    DateTimeInput,
    FileInput,
    ImageInput,
    MarkdownInput,
    NumberInput,
    PasswordInput,
    PhoneInput,
    RadioInput,
    SelectInput,
    SwitchInput,
    TagsInput,
    TagsListboxInput,
    TextareaInput,
    TextInput,
} from '@/components/form';
import {
    FormHeaderBase,
    HeaderActions,
    Heading,
    HeadingTitle,
} from '@/components/form-header';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import {
    Field,
    FieldGroup,
    FieldSeparator,
    FieldSet,
} from '@/components/ui/field';
import { Spinner } from '@/components/ui/spinner';
import AdminLayout from '@/layouts/AdminLayout.vue';
import { cn, validate } from '@/lib/utils';
import testForm from '@/routes/admin/test-form';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const title = 'Formulario de prueba';
const buttonLabel = 'Crear';

const breadcrumb = [
    {
        name: title,
    },
];

const form = useForm<App.Data.TestFormData>({
    text: undefined,
    textarea: undefined,
    taglistbox: null,
    tags: null,
    switch: false,
    select: null,
    radio: null,
    phone: undefined,
    password: undefined,
    number: undefined,
    markdown: undefined,
    image: null,
    is_new_image: false,
    file: null,
    combobox: null,
    checkbox: false,
    date: null,
    datetime: null,
}).withPrecognition(testForm.store());

const submit = () => form.submit();

form.validateFiles();

const currentImageUrl = ref(form.image);

const validateField = validate(form);

const options = [
    { name: 'Option 1', id: 1 },
    { name: 'Option 2', id: 2 },
    { name: 'Option 3', id: 3 },
];
</script>

<template>
    <AdminLayout :breadcrumb="breadcrumb" :title="title" container="fit">
        <div>
            <FormHeaderBase>
                <Heading>
                    <HeadingTitle>
                        {{ title }}
                    </HeadingTitle>
                </Heading>

                <HeaderActions>
                    <Button
                        :disabled="form.processing || form.validating"
                        type="submit"
                        @click="submit"
                    >
                        {{ buttonLabel }}
                        <Spinner v-if="form.validating || form.processing" />
                    </Button>
                </HeaderActions>
            </FormHeaderBase>

            <form
                :class="
                    cn(
                        'grid gap-4',
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
                                    <TextInput
                                        id="text"
                                        v-model="form.text"
                                        :error="form.errors.text"
                                        label="Texto"
                                        placeholder="Simple texto"
                                        type="text"
                                        @validate="validateField('text')"
                                    />

                                    <TextareaInput
                                        id="textarea"
                                        v-model="form.textarea"
                                        :error="form.errors.textarea"
                                        label="Textos largos"
                                        placeholder="Tu contenido"
                                        @validate="validateField('textarea')"
                                    />

                                    <TagsListboxInput
                                        v-model="form.taglistbox"
                                        :error="form.errors.taglistbox"
                                        :options="options"
                                        label="Multiples opciones"
                                        placeholder="Selecciona etiquetas"
                                        @validate="validateField('taglistbox')"
                                    />

                                    <TagsInput
                                        v-model="form.tags"
                                        :error="form.errors.tags"
                                        :max="5"
                                        label="Etiquetas"
                                        @validate="validateField('tags')"
                                    />

                                    <div class="rounded-md border p-4">
                                        <SwitchInput
                                            id="switch"
                                            v-model="form.switch"
                                            :error="form.errors.switch"
                                            description="Lorem ipsum dolor sit amet consectetur adipisicing elit."
                                            label="Falso o verdadero"
                                            @validate="validateField('switch')"
                                        />
                                    </div>

                                    <SelectInput
                                        v-model="form.select"
                                        :error="form.errors.select"
                                        :options="options"
                                        label="Opciones"
                                        @validate="validateField('select')"
                                    />

                                    <RadioInput
                                        v-model="form.radio"
                                        :error="form.errors.radio"
                                        :options="options"
                                        label="Opciones"
                                        @validate="validateField('radio')"
                                    />

                                    <PhoneInput
                                        id="phone"
                                        v-model="form.phone"
                                        :error="form.errors.phone"
                                        label="Teléfono"
                                        @validate="validateField('phone')"
                                    />

                                    <PasswordInput
                                        id="password"
                                        v-model="form.password"
                                        :error="form.errors.password"
                                        label="Contraseña"
                                        @validate="validateField('password')"
                                    />

                                    <NumberInput
                                        id="number"
                                        v-model="form.number"
                                        :error="form.errors.number"
                                        label="Número"
                                        @validate="validateField('number')"
                                    />

                                    <MarkdownInput
                                        id="markdown"
                                        v-model="form.markdown"
                                        :error="form.errors.markdown"
                                        label="Contenido"
                                        @validate="validateField('markdown')"
                                    />

                                    <ImageInput
                                        v-model="form.image"
                                        v-model:isNewImage="form.is_new_image"
                                        :currentImageUrl="currentImageUrl"
                                        :error="form.errors.image"
                                        description="PNG, JPG, WEBP (máx. 20 MB)"
                                        label="Thumbnail"
                                        @validate="validateField('image')"
                                    />

                                    <FileInput
                                        id="file"
                                        v-model="form.file"
                                        :error="form.errors.file"
                                        description="Cualquier archivo"
                                        label="Archivo"
                                        @validate="validateField('file')"
                                    />

                                    <ComboboxInput
                                        id="combobox"
                                        v-model="form.combobox"
                                        :error="form.errors.combobox"
                                        :options="options"
                                        label="Combobox"
                                        @validate="validateField('combobox')"
                                    />

                                    <CheckboxInput
                                        id="checkbox"
                                        v-model="form.checkbox"
                                        :error="form.errors.checkbox"
                                        label="Checkbox"
                                        @validate="validateField('checkbox')"
                                    />

                                    <DateInput
                                        v-model="form.date"
                                        :error="form.errors.date"
                                        label="Fecha"
                                        @validate="validateField('date')"
                                    />

                                    <DateTimeInput
                                        v-model="form.datetime"
                                        :error="form.errors.datetime"
                                        label="Fecha y hora"
                                        @validate="validateField('datetime')"
                                    />
                                </FieldGroup>
                            </FieldSet>

                            <FieldSeparator />

                            <Field orientation="horizontal">
                                <Button
                                    :disabled="
                                        form.processing || form.validating
                                    "
                                    type="submit"
                                >
                                    {{ buttonLabel }}
                                    <Spinner
                                        v-if="
                                            form.validating || form.processing
                                        "
                                    />
                                </Button>
                            </Field>
                        </FieldGroup>
                    </CardContent>
                </Card>
            </form>
        </div>
    </AdminLayout>
</template>
