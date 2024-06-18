<script setup>
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';

import Textarea from 'primevue/textarea';
import Button from 'primevue/button';

import { useForm } from '@inertiajs/vue3';

const emit = defineEmits(['close-contact-dialog']);

const form = useForm({
    first_name: null,
    last_name: null,
    email: null,
    phone_number: null,
    question: null,
});

const submitForm = () => {
    form.post(route('support.store'), {
        onSuccess: () => emit('close-contact-dialog'),
        onError: (error) => console.log(error),
    })
};

</script>

<template>
    <p class="mb-6">Get in touch with us about any of our adventures! Our Travel Experts will get back to you shortly.</p>
    <form @submit.prevent="submitForm">
        <div class="flex align-items-center gap-4 mb-3">
            <TextInput v-model="form.first_name" label="First Name" :error="form.errors.first_name" />
            <TextInput v-model="form.last_name" label="Last Name" :error="form.errors.last_name" />
        </div>
        <div class="flex align-items-center gap-4 mb-6">
            <TextInput v-model="form.email" label="Email" :error="form.errors.email" />
            <TextInput v-model="form.phone_number" label="Phone Number" :error="form.errors.phone_number" />
        </div>
        <div class="flex flex-col mb-5">
            <label class="text-neutral-500 text-sm mb-1 ml-2">Your Question</label>
            <Textarea v-model="form.question" rows="5" />
            <InputError :message="form.errors.question" />
        </div>
        <div class="flex justify-end gap-2">
            <Button type="submit" label="Submit" />
        </div>
    </form>
</template>