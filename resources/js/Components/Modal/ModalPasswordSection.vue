<script setup>
import Button from 'primevue/button';
import PasswordInput from '@/Components/PasswordInput.vue';
import { useForm } from '@inertiajs/vue3';

const emit = defineEmits(['close']);

const props = defineProps({
    email: {
        type: String,
        required: true
    }
});

const form = useForm({
    email: props.email,
    password: null,
});

const submitPassword = () => {
    form.post(route('login'), {
        onSuccess: () => emit('close'),
        onFinish: () => form.reset('password'),
    })
}

</script>

<template>
    <div class="flex flex-col justify-center items-center p-6 mb-6">
        <div class="flex lg:justify-center lg:col-start-2 mb-4">
            <ApplicationLogo />
        </div>
        <h2 class="font-bold text-2xl mb-2">Welcome Back</h2>
        <p class="mb-6">Enter your password</p>
        <form @submit.prevent="submitPassword">
            <PasswordInput
                v-model="form.password"
                placeholder="Password"
                :error="form.errors.password"
                required
                class="mb-4"
            />
            <Button
                type="submit"
                label="Confirm"
                :disabled="form.processing"
                class="bg-primary border-primary w-full"
            />
        </form>
        <small class="mt-2 cursor-pointer">
            <a href="#" class="underline">Forgot Password?</a>
        </small>
        <small class="text-center mt-12">
            By signing in, you are agreeing to our <br> 
            <a href="#" class="underline">Privacy Policy</a>, and 
            <a href="#" class="underline">Terms of Use</a>.
        </small>
    </div>
</template>