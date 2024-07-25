<script setup>
import TextInput from '@/Components/TextInput.vue';

import Button from 'primevue/button';

import { useForm } from '@inertiajs/vue3';

const form = useForm({
    first_name: null,
    last_name: null,
    email: null,
    phone_number: null,
    business_name: null,
    website: null,
    country: null,
    city: null,
    message: null,
});

const submit = () => {
    form.post(route('partner.validate'), {
        onSuccess: () => form.reset(),
    });
};

</script>

<template>
    <div class="p-6">
        <h1 class="text-center text-xl text-primary">Partner With Us!</h1>
    </div>
    <form @submit.prevent="submit">
        <div class="my-4">
            <div class="flex gap-6 mb-2">
                <TextInput v-model="form.first_name" name="first_name" label="First Name" :error="form.errors.first_name" required />
                <TextInput v-model="form.last_name" name="last_name" label="Last Name" :error="form.errors.last_name" required />
            </div>
            <div class="flex gap-6 mb-2">
                <TextInput v-model="form.email" name="email" label="Email Address" :error="form.errors.email" required />
                <TextInput v-model="form.phone_number" name="phone_number" label="Phone Number" :error="form.errors.phone_number" required />
            </div>
            <div class="flex gap-6 mb-2">
                <TextInput v-model="form.business_name" name="business_name" label="Business Name" :error="form.errors.business_name" required />
                <TextInput v-model="form.website" name="website" label="Website" placeholder="https://domain.com" :error="form.errors.website" required />
            </div>
            <div class="flex gap-6 mb-2">
                <TextInput v-model="form.country" name="country" label="Country" :error="form.errors.country" required />
                <TextInput v-model="form.city" name="city" label="City" :error="form.errors.city" required />
            </div>
            <TextInput v-model="form.message" name="message" label="Message" type="textarea" :error="form.errors.message" />
        </div>
        <div class="flex flex-col gap-4 justify-center items-center">
            <Button dusk="submit-button" label="Submit" type="submit" class="mx-auto" />
            <small>Already a partner? 
                <a class="underline cursor-pointer" :href="route('partner-login.create')">Login here.</a>
            </small>
        </div>
    </form>
</template>