<script lang="ts" setup>
import { Container } from '@/components/container';
import ContactForm from '@/components/form/ContactForm.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { cn } from '@/lib/utils';
import contact from '@/routes/contact';
import { useForm } from '@inertiajs/vue3';

const head = {
    title: 'Contacto',
};

const form = useForm<App.Data.SendContactData>({
    name: '',
    email: '',
    subject: '',
    message: '',
}).withPrecognition(contact.store());

const submit = () =>
    form.submit({
        preserveState: false,
        onSuccess: () => {
            form.reset();
        },
    });
</script>

<template>
    <AppLayout :head="head">
        <Container class="max-w-2xl">
            <form
                :class="
                    cn(form.processing ? 'pointer-events-none opacity-50' : '')
                "
                @submit.prevent="submit"
            >
                <ContactForm :form="form" />
            </form>
        </Container>
    </AppLayout>
</template>
