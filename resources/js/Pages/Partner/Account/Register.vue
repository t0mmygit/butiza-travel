<script setup>
import NavBar from '@/Components/NavBar.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';

import Button from 'primevue/button';

const props = defineProps({
    partner: {
        type: [Array, Object, null],
    }
});

const form = useForm({
    first_name: props.partner?.first_name ?? null,
    last_name: props.partner?.last_name ?? null,
    email: props.partner?.email ?? null,
    phone_number: props.partner?.phone_number ?? null,
    password: null,
    password_confirmation: null,
    role: 'partner',
});

const submit = () => {
    form.post(route('signup.detail'), {
        onSuccess: () => form.reset(),
        onError: () => form.reset('first_name', 'last_name', 'email'),
    });
}

</script>

<template>
    <GuestLayout>
        <form @submit.prevent="submit">
            <div class="flex flex-col gap-4 p-4">
                <TextInput v-model="form.first_name" label="First Name" :error="form.errors.first_name" required />
                <TextInput v-model="form.last_name" label="Last Name" :error="form.errors.last_name" required />
                <TextInput v-model="form.email" label="Email Address" :error="form.errors.email" required />
                <TextInput v-model="form.password" label="Password" type="password" :error="form.errors.password" required />
                <TextInput v-model="form.password_confirmation" label="Confirm Password" type="password" :error="form.errors.confirm_password" required />
                <div class="flex justify-center">
                    <Button type="submit" label="Register" class="w-full" />
                </div>
            </div>
        </form>
    </GuestLayout>
</template>