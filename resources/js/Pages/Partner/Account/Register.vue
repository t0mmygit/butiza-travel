<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import TextInput from '@/Components/TextInput.vue';
import PasswordInput from '@/Components/PasswordInput.vue';
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
});

const partnerForm = useForm({
    business_name: props.partner.business_name,
    website: props.partner.website,
    country: props.partner.country,
    city: props.partner.city,
    message: props.partner.message ?? null,
});

const submit = () => {
    form.post(route('partner-account.store'), {
        onSuccess: () => partnerForm.post(route('partner.store')),
        // TODO: Handle in case of failure
        onError: (error) => console.error(error),
        onFinish: () => form.reset('password', 'password_confirmation'),
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
                <TextInput v-model="form.phone_number" label="Phone Number" :error="form.errors.phone_number" required />
                <PasswordInput 
                    v-model="form.password" 
                    name="password" 
                    label="Password" 
                    :error="form.errors.password" 
                    required
                />
                <PasswordInput 
                    v-model="form.password_confirmation" 
                    name="password_confirmation" 
                    label="Confirm Password" 
                    :error="form.errors.confirm_password" 
                    required
                />
                <div class="flex justify-center">
                    <Button dusk="submit-button" type="submit" label="Register" class="w-full" />
                </div>
            </div>
        </form>
    </GuestLayout>
</template>