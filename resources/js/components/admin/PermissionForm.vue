<script setup lang="ts">
import type { InertiaPrecognitiveForm } from '@inertiajs/vue3';
import { TextInput } from '@/components/form';
import { Card, CardContent } from '@/components/ui/card';
import { FieldGroup } from '@/components/ui/field';

/**
 * Fields shared by the create and edit permission pages.
 */
const { form } = defineProps<{
    form: InertiaPrecognitiveForm<App.Data.PermissionFormData>;
}>();
</script>

<template>
    <Card>
        <CardContent>
            <FieldGroup>
                <TextInput
                    id="name"
                    v-model="form.name"
                    label="Nombre"
                    description="Formato recurso.acción o recurso.acción.atributo (p. ej. posts.publish). Se asigna a los roles desde su formulario."
                    :error="form.errors.name"
                    @validate="form.validate('name')"
                />
            </FieldGroup>
        </CardContent>
    </Card>
</template>
