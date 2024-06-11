<script setup>
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import Textarea from 'primevue/textarea';

const form = useForm({
    firstName: null,
    lastName: null,
    email: null,
    phone_number: null,
    question: null,
});

</script>

<template>
    <p class="mb-6">Get in touch with us about any of our adventures! Our Travel Experts will get back to you shortly.</p>
    <form>
        <div class="flex align-items-center gap-3 mb-3">
            <TextInput v-model="form.firstName" label="First Name" />
            <TextInput v-model="form.lastName" label="Last Name" />
        </div>
        <div class="flex align-items-center gap-3 mb-3">
            <TextInput v-model="form.email" label="Email" />
            <TextInput v-model="form.phone_number" label="Phone Number" />
        </div>
        <div class="flex flex-col">
            <label class="text-neutral-500 text-sm mb-1 ml-2">Your Question</label>
            <Textarea v-model="form.question" rows="5" />
        </div>
    </form>
</template>