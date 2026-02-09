<script lang="ts" setup>
import ProfileController from '@/actions/App/Http/Controllers/Settings/ProfileController';
import { Form } from '@inertiajs/vue3';
import { useTemplateRef } from 'vue';

// Components
import HeadingSmall from '@/components/HeadingSmall.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

const passwordInput = useTemplateRef('passwordInput');
</script>

<template>
    <div class="space-y-6">
        <HeadingSmall
            description="Elimina tu cuenta y todos sus recursos"
            title="Eliminar cuenta"
        />
        <div
            class="space-y-4 rounded-lg border border-red-100 bg-red-50 p-4 dark:border-red-200/10 dark:bg-red-700/10"
        >
            <div class="relative space-y-0.5 text-red-600 dark:text-red-100">
                <p class="font-medium">Advertencia</p>
                <p class="text-sm">
                    Por favor, proceda con precaución, esto no se puede
                    deshacer.
                </p>
            </div>
            <Dialog>
                <DialogTrigger as-child>
                    <Button
                        data-test="delete-user-button"
                        variant="destructive"
                    >
                        Eliminar cuenta
                    </Button>
                </DialogTrigger>
                <DialogContent>
                    <Form
                        v-slot="{ errors, processing, reset, clearErrors }"
                        :options="{
                            preserveScroll: true,
                        }"
                        class="space-y-6"
                        reset-on-success
                        v-bind="ProfileController.destroy.form()"
                        @error="() => passwordInput?.$el?.focus()"
                    >
                        <DialogHeader class="space-y-3">
                            <DialogTitle>
                                ¿Estás seguro de que deseas eliminar tu cuenta?
                            </DialogTitle>
                            <DialogDescription>
                                Una vez que su cuenta sea eliminada, todos sus
                                recursos y datos también serán eliminados
                                permanentemente. Por favor, ingrese su
                                contraseña para confirmar que desea eliminar
                                permanentemente su cuenta.
                            </DialogDescription>
                        </DialogHeader>

                        <div class="grid gap-2">
                            <Label class="sr-only" for="password">
                                Contraseña
                            </Label>
                            <Input
                                id="password"
                                ref="passwordInput"
                                name="password"
                                placeholder="Password"
                                type="password"
                            />
                            <InputError :message="errors.password" />
                        </div>

                        <DialogFooter class="gap-2">
                            <DialogClose as-child>
                                <Button
                                    variant="secondary"
                                    @click="
                                        () => {
                                            clearErrors();
                                            reset();
                                        }
                                    "
                                >
                                    Cancelar
                                </Button>
                            </DialogClose>

                            <Button
                                :disabled="processing"
                                data-test="confirm-delete-user-button"
                                type="submit"
                                variant="destructive"
                            >
                                Eliminar cuenta
                            </Button>
                        </DialogFooter>
                    </Form>
                </DialogContent>
            </Dialog>
        </div>
    </div>
</template>
