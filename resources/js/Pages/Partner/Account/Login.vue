<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';

import TextInput from '@/Components/TextInput.vue';
import Checkbox from '@/Components/Checkbox.vue';

import Button from 'primevue/button';

import { Head, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: null,
    password: null,
    remember: false,
});

const submit = () => {
    form.post(route('partner-login.store'), {
        onFinish: () => form.reset('password'),
    });
};  

</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <h1 class="mb-2 font-medium text-center">
            Login as Partner
        </h1>

        <p class="mb-4 text-success-600 text-center">
            You are logged in.
        </p>

        <form v-if="!status" @submit.prevent="submit">
            <div class="flex flex-col gap-4">
                <TextInput
                    v-model="form.email"
                    label="Email"
                    type="email"
                    name="email"
                    autofocus
                    required
                    :error="form.errors.email"
                    class="mt-1 block w-full"
                />
                <TextInput
                    v-model="form.password"
                    label="Password"
                    type="password"
                    name="password"
                    autofocus
                    required
                    :error="form.errors.password"
                    class="mt-1 block w-full"
                />
            </div>

            <div class="flex items-center justify-between mt-4">
                <label class="flex items-center">
                    <Checkbox 
                        v-model:checked="form.remember" 
                        label="Remember me"
                    />
                </label>
                <a
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="underline text-sm text-neutral-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    Forgot your password?
                </a>
            </div>

            <div class="flex flex-col w-full items-center justify-center mt-8 gap-2">
                <Button 
                    dusk="login-button"
                    label="Log in"
                    type="submit"
                    class="w-full" 
                    :class="{ 'opacity-25': form.processing }" 
                    :disabled="form.processing"
                />
                <Button
                    label="Request a Partnership"
                    type="button"
                    class="w-full text-sm" text outlined
                    @click="$inertia.get(route('partner.create'))"
                    :disabled="form.processing"
                />
            </div>
        </form>
        <div class="flex justify-center"> 
            <Button 
                label="To Partner Account" 
                text outlined 
                @click="$inertia.get(route('partner-account'))"
            />
        </div>
    </GuestLayout>
</template>