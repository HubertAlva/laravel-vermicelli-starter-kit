<script lang="ts" setup>
import { TextInput } from '@/components/form/index';
import PasswordInput from '@/components/form/PasswordInput.vue';
import SelectInput from '@/components/form/SelectInput.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { Field, FieldGroup, FieldSet } from '@/components/ui/field';
import { Spinner } from '@/components/ui/spinner';
import { validate } from '@/lib/utils';
import { InertiaPrecognitiveForm } from '@inertiajs/vue3';
import { toRef } from 'vue';

const props = defineProps<{
    form: InertiaPrecognitiveForm<App.Data.UserFormData>;
    buttonLabel: string;
}>();

const form = toRef(props, 'form');

const validateField = validate(props.form);

const roles = [{ label: 'Administrador', value: 'admin' }];
</script>

<template>
    <Card>
        <CardContent>
            <FieldGroup>
                <FieldSet>
                    <FieldGroup>
                        <div
                            class="grid gap-7 @min-lg:grid-cols-2 @min-lg:gap-4 @min-lg:gap-y-7"
                        >
                            <SelectInput
                                label="Rol"
                                placeholder="Selecciona un rol"
                                v-model="form.role"
                                :options="roles"
                                :error="form.errors.role"
                                @validate="validateField('role')"
                            />

                            <TextInput
                                id="name"
                                v-model="form.name"
                                :error="form.errors.name"
                                label="Nombre"
                                placeholder="Hubert Alva"
                                type="text"
                                @change="validateField('name')"
                            />

                            <TextInput
                                id="email"
                                v-model="form.email"
                                :error="form.errors.email"
                                label="Correo electrónico"
                                placeholder="micorreo@gmail.com"
                                type="email"
                                @change="validateField('email')"
                            />

                            <PasswordInput
                                id="password"
                                v-model="form.password"
                                :error="form.errors.password"
                                label="Contraseña"
                                @change="validateField('password')"
                            />

                            <PasswordInput
                                id="password_confirmation"
                                v-model="form.password_confirmation"
                                :error="form.errors.password_confirmation"
                                label="Confirmar contraseña"
                                @change="validateField('password_confirmation')"
                            />
                        </div>
                    </FieldGroup>
                </FieldSet>

                <Field orientation="horizontal">
                    <Button
                        type="submit"
                        :disabled="form.processing || form.validating"
                    >
                        {{ buttonLabel }}
                        <Spinner v-if="form.validating || form.processing" />
                    </Button>
                </Field>
            </FieldGroup>
        </CardContent>
    </Card>
</template>
