<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import SubmitButton from '@/components/SubmitButton.vue';
import { TextareaInput, TextInput } from '@/components/form';
import { Card, CardContent } from '@/components/ui/card';
import { FieldGroup } from '@/components/ui/field';
import { cn } from '@/lib/utils';
import contact from '@/routes/contact';

const form = useForm<App.Data.ContactData>({
    name: '',
    email: '',
    subject: '',
    message: '',
})
    .withPrecognition(contact.store())
    .setValidationTimeout(500);

function submit(): void {
    form.submit({
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
}
</script>

<template>
    <form
        :class="cn(form.processing && 'pointer-events-none opacity-50')"
        @submit.prevent="submit"
    >
        <Card>
            <CardContent>
                <FieldGroup>
                    <div class="grid gap-7 sm:grid-cols-2 sm:gap-4">
                        <TextInput
                            id="name"
                            v-model="form.name"
                            label="Nombre"
                            autocomplete="name"
                            :error="form.errors.name"
                            @validate="form.validate('name')"
                        />

                        <TextInput
                            id="email"
                            v-model="form.email"
                            type="email"
                            label="Correo electrónico"
                            autocomplete="email"
                            :error="form.errors.email"
                            @validate="form.validate('email')"
                        />
                    </div>

                    <TextInput
                        id="subject"
                        v-model="form.subject"
                        label="Asunto"
                        :error="form.errors.subject"
                        @validate="form.validate('subject')"
                    />

                    <TextareaInput
                        id="message"
                        v-model="form.message"
                        label="Mensaje"
                        placeholder="Escribe tu mensaje aquí..."
                        :error="form.errors.message"
                        @validate="form.validate('message')"
                    />

                    <div>
                        <SubmitButton :processing="form.processing"
                            >Enviar</SubmitButton
                        >
                    </div>
                </FieldGroup>
            </CardContent>
        </Card>
    </form>
</template>
