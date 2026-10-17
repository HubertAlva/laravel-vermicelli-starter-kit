<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import FormHeader from '@/components/admin/FormHeader.vue';
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
import SubmitButton from '@/components/SubmitButton.vue';
import { Card, CardContent } from '@/components/ui/card';
import { FieldGroup } from '@/components/ui/field';
import { cn } from '@/lib/utils';
import testForm from '@/routes/admin/test-form';

defineOptions({
    layout: {
        container: 'fit',
        breadcrumbs: [
            { title: 'Formulario de prueba', href: testForm.index() },
        ],
    },
});

const form = useForm<App.Data.TestFormData>({
    text: null,
    textarea: null,
    taglistbox: null,
    tags: null,
    switch: false,
    select: null,
    radio: null,
    phone: null,
    password: null,
    number: null,
    markdown: null,
    image: null,
    is_new_image: false,
    file: null,
    combobox: null,
    checkbox: false,
    date: null,
    datetime: null,
})
    .withPrecognition(testForm.store())
    .setValidationTimeout(500)
    .validateFiles();

const options = [
    { id: 1, name: 'Opción 1' },
    { id: 2, name: 'Opción 2' },
    { id: 3, name: 'Opción 3' },
];
</script>

<template>
    <Head title="Formulario de prueba" />

    <form
        :class="cn(form.processing && 'pointer-events-none opacity-50')"
        @submit.prevent="form.submit({ preserveScroll: true })"
    >
        <FormHeader title="Formulario de prueba">
            <template #actions>
                <SubmitButton :processing="form.processing"
                    >Enviar</SubmitButton
                >
            </template>
        </FormHeader>

        <Card>
            <CardContent>
                <FieldGroup>
                    <TextInput
                        id="text"
                        v-model="form.text"
                        label="Texto"
                        :error="form.errors.text"
                        @validate="form.validate('text')"
                    />

                    <TextareaInput
                        id="textarea"
                        v-model="form.textarea"
                        label="Texto largo"
                        :error="form.errors.textarea"
                        @validate="form.validate('textarea')"
                    />

                    <TagsListboxInput
                        id="taglistbox"
                        v-model="form.taglistbox"
                        label="Selección múltiple"
                        :options
                        :error="form.errors.taglistbox"
                        @validate="form.validate('taglistbox')"
                    />

                    <TagsInput
                        id="tags"
                        v-model="form.tags"
                        label="Etiquetas"
                        :max="3"
                        :error="form.errors.tags"
                        @validate="form.validate('tags')"
                    />

                    <div class="rounded-md border p-4">
                        <SwitchInput
                            id="switch"
                            v-model="form.switch"
                            label="Interruptor"
                            description="Debe estar activado."
                            :error="form.errors.switch"
                            @validate="form.validate('switch')"
                        />
                    </div>

                    <SelectInput
                        id="select"
                        v-model="form.select"
                        label="Selección"
                        :options
                        :error="form.errors.select"
                        @validate="form.validate('select')"
                    />

                    <RadioInput
                        id="radio"
                        v-model="form.radio"
                        label="Opciones"
                        :options
                        :error="form.errors.radio"
                        @validate="form.validate('radio')"
                    />

                    <ComboboxInput
                        id="combobox"
                        v-model="form.combobox"
                        label="Combobox"
                        :options
                        :error="form.errors.combobox"
                        @validate="form.validate('combobox')"
                    />

                    <PhoneInput
                        id="phone"
                        v-model="form.phone"
                        label="Teléfono"
                        :error="form.errors.phone"
                        @validate="form.validate('phone')"
                    />

                    <PasswordInput
                        id="password"
                        v-model="form.password"
                        label="Contraseña"
                        :error="form.errors.password"
                        @validate="form.validate('password')"
                    />

                    <NumberInput
                        id="number"
                        v-model="form.number"
                        label="Número"
                        :min="1"
                        :max="100"
                        :error="form.errors.number"
                        @validate="form.validate('number')"
                    />

                    <DateInput
                        id="date"
                        v-model="form.date"
                        label="Fecha"
                        :error="form.errors.date"
                        @validate="form.validate('date')"
                    />

                    <DateTimeInput
                        id="datetime"
                        v-model="form.datetime"
                        label="Fecha y hora"
                        :error="form.errors.datetime"
                        @validate="form.validate('datetime')"
                    />

                    <MarkdownInput
                        id="markdown"
                        v-model="form.markdown"
                        label="Markdown"
                        :error="form.errors.markdown"
                        @validate="form.validate('markdown')"
                    />

                    <ImageInput
                        id="image"
                        v-model="form.image"
                        v-model:is-new-image="form.is_new_image"
                        label="Imagen"
                        description="PNG, JPG o WEBP (máx. 10 MB)"
                        :error="form.errors.image"
                        @validate="form.validate('image')"
                    />

                    <FileInput
                        id="file"
                        v-model="form.file"
                        label="Archivo"
                        description="Cualquier archivo (máx. 512 KB)"
                        :error="form.errors.file"
                        @validate="form.validate('file')"
                    />

                    <CheckboxInput
                        id="checkbox"
                        v-model="form.checkbox"
                        label="Acepto los términos"
                        :error="form.errors.checkbox"
                        @validate="form.validate('checkbox')"
                    />
                </FieldGroup>
            </CardContent>
        </Card>
    </form>
</template>
