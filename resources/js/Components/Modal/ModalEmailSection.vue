<script setup>
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import InputError from '@/Components/InputError.vue';
import InputGroup from 'primevue/inputgroup';
import InputGroupAddon from 'primevue/inputgroupaddon';
import axios from 'axios';
import { computed, ref } from 'vue';

const emit = defineEmits(['confirm', 'password']);

const errorMessage = ref(null);

const form = ref({
    email: null,
});

const error = computed(() => errorMessage.value);

const submitForm = () => {
    axios.post(route('auth.email', form.value))
        .then(response => {
            if (!response.data) emit('confirm', form.value.email);
            else emit('password', form.value.email);
            form.value = null;
        })
        .catch(error => {
            errorMessage.value = error.response.data.message;
            console.error(error);
        });
}

</script>

<template>
    <div class="flex flex-col justify-center items-center p-6 mb-6">
        <div class="flex lg:justify-center lg:col-start-2 mb-4">
            <SvgLogo />
        </div>
        <h2 class="font-bold text-2xl mb-2">Sign in or create an account</h2>
        <p class="mb-6">Access member savings & community.</p>
        <form @submit.prevent="submitForm">
            <InputGroup class="group max-w-xs">
                <InputGroupAddon class="group-hover:border-primary group-focus:border-primary group-active:border-primary">
                    <i class="pi pi-envelope"></i>
                </InputGroupAddon>
                <InputText
                    v-model="form.email"
                    type="email"
                    placeholder="Email Address"
                    class="group-hover:border-primary group-focus:border-primary group-active:border-primary transition-none"
                />
                <Button
                    type="submit"
                    label="Confirm"
                    class="bg-primary border-primary"
                />
            </InputGroup>
            <InputError :message="error" class="mt-2" />
        </form>
        <div class="my-6">or</div>
        <div class="flex flex-col gap-4">
            <Button 
                label="Continue with Facebook" 
                icon="pi pi-facebook"
                plain text raised 
                class="shadow-none text-base outline outline-1 outline-gray-300 rounded hover:outline-primary hover:bg-white"
            />
            <Button
                label="Continue with Google"
                icon="pi pi-google"
                plain text raised
                class="shadow-none text-base outline outline-1 outline-gray-300 rounded hover:outline-primary hover:bg-white"
            />
        </div>
        <small class="text-gray-500 text-center mt-12">
            By signing in, you are agreeing to our <br> 
            <a href="#" class="underline">Privacy Policy</a>, and 
            <a href="#" class="underline">Terms of Use</a>.
        </small>
    </div>
</template>