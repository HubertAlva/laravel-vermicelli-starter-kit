<script lang="ts" setup>
import { TextInput } from '@/components/form/index';
import TextareaInput from '@/components/form/TextareaInput.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { Field, FieldGroup, FieldSet } from '@/components/ui/field';
import { Spinner } from '@/components/ui/spinner';
import { validate } from '@/lib/utils';
import { InertiaPrecognitiveForm } from '@inertiajs/vue3';
import { toRef } from 'vue';

const props = defineProps<{
    form: InertiaPrecognitiveForm<App.Data.SendContactData>;
}>();

const form = toRef(props, 'form');

const validateField = validate(props.form);
</script>

<template>
    <Card>
        <CardContent>
            <FieldGroup>
                <FieldSet>
                    <FieldGroup>
                        <div class="grid grid-cols-2 gap-4">
                            <TextInput
                                id="name"
                                v-model="form.name"
                                :error="form.errors.name"
                                label="Nombre"
                                placeholder="Tu nombre"
                                type="text"
                                @change="validateField('name')"
                            />

                            <TextInput
                                id="email"
                                v-model="form.email"
                                :error="form.errors.email"
                                label="Correo electrónico"
                                placeholder="Tu correo electrónico"
                                type="email"
                                @change="validateField('email')"
                            />
                        </div>

                        <TextInput
                            id="subject"
                            v-model="form.subject"
                            :error="form.errors.subject"
                            label="Asunto"
                            placeholder="Asunto del mensaje"
                            type="text"
                            @change="validateField('subject')"
                        />

                        <TextareaInput
                            id="message"
                            v-model="form.message"
                            :error="form.errors.message"
                            label="Mensaje"
                            placeholder="Escribe tu mensaje aquí..."
                            @change="validateField('message')"
                        />

                        <Field orientation="horizontal">
                            <Spinner
                                v-if="form.validating || form.processing"
                            />
                            <Button :disabled="form.processing" type="submit">
                                Enviar
                            </Button>
                        </Field>
                    </FieldGroup>
                </FieldSet>
            </FieldGroup>
        </CardContent>
    </Card>
</template>
