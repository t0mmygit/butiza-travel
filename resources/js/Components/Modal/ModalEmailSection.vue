<script setup>
import Button from 'primevue/button';
import TextInput from '@/Components/TextInput.vue';
import Divider from 'primevue/divider'; 

import axios from 'axios';
import { computed, ref } from 'vue';

const emit = defineEmits(['confirm', 'password']);

const errorMessage = ref(null);

const form = ref({
    email: null,
});

const error = computed(() => errorMessage.value);

const submit = () => {
    axios.post(route('email.validate', form.value))
        .then(response => {
            if (!response.data) emit('confirm', form.value.email);
            else emit('password', form.value.email);
            form.value = null;
        })
        .catch(error => {
            errorMessage.value = error.response.data.message;
            console.error(error);
        });
};

const openAuthorize = (provider) => {
    window.location.href = route('auth.redirect', { provider: provider });
};

</script>

<template>
    <div class="flex flex-col p-6 mx-auto">
        <div class="flex lg:justify-center lg:col-start-2 mb-4">
            <ApplicationLogo />
        </div>
        <div class="flex flex-col text-center gap-2 mb-4">
            <h2 class="font-bold text-2xl">Sign in or create an account</h2>
            <p>Access member savings & community.</p>
        </div>
        <div class="flex flex-col justify-center max-w-sm mx-auto">
            <form @submit.prevent="submit">
                <TextInput
                    v-model="form.email"
                    type="email"
                    placeholder="Email Address"
                    icon="pi pi-envelope"
                    :error="error"
                    required
                />
                <Button
                    label="Submit"
                    type="submit"
                    :disabled="form.processing"
                    class="mt-2 w-full"
                />
            </form>
            <Divider class="my-8">or</Divider>
            <div class="flex gap-4 justify-center mb-6">
                <Button
                    icon="pi pi-facebook"
                    text plain rounded outlined
                    @click="openAuthorize('facebook')"
                    disabled
                />
                <Button
                    icon="pi pi-google"
                    text plain rounded outlined
                    @click="openAuthorize('google')"
                    disabled
                />
                <Button
                    icon="pi pi-github"
                    text plain rounded outlined
                    @click="openAuthorize('github')"
                />
            </div>
            <small class="text-gray-500 text-center">
                By signing in, you are agreeing to our <br>
                <a href="#" class="underline">Privacy Policy</a>, and
                <a href="#" class="underline">Terms of Use</a>.
            </small>
        </div>
    </div>
</template>