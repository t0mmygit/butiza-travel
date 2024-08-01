<script setup>
import FloatInput from '@/Components/FloatInput.vue';
import { router, useForm } from '@inertiajs/vue3';
import Button from 'primevue/button';

const emit = defineEmits(['close']);

const props = defineProps({
    email: {
        type: String,
        required: true
    }
});

const form = useForm({
    email: props.email,
    first_name: null,
    last_name: null,
    password: null,
    password_confirmation: null
});

const submitDetail = () => {
    form.post(route('auth.store'), {
        onSuccess: () => emit('close'),
        onError: (error) => console.error(error),
        onFinish: () => form.reset(),
    })
};

</script>

<template>
    <div class="flex flex-col justify-center items-center p-6 mb-6">
        <div class="flex lg:justify-center lg:col-start-2 mb-4">
            <ApplicationLogo />
        </div>
        <h2 class="font-bold text-2xl mb-2">Join us. It's free.</h2>
        <p class="mb-12">Access member savings & community.</p>
        <form @submit.prevent="submitDetail">
            <div class="flex flex-col gap-4 mb-4">
                <FloatInput v-model="form.first_name" label="First Name" value="firstName" :error="form.errors.first_name" />
                <FloatInput v-model="form.last_name" label="Last Name" value="lastName" :error="form.errors.last_name" />
                <FloatInput v-model="form.password" type="password" value="password" label="Password" :error="form.errors.password" />
                <FloatInput v-model="form.password_confirmation" type="password" value="confirmPassword" label="Confirm Password" :error="form.errors.password_confirmation" />
                <Button type="submit" label="Join" />
            </div>
        </form>
        <a @click="$emit('previous')" class="text-gray-500 text-center text-sm underline cursor-pointer">Sign Up with Facebook or Google instead?</a>
        <small class="text-gray-500 text-center mt-12">
            By signing in, you are agreeing to our <br> 
            <a href="#" class="underline">Privacy Policy</a>, and 
            <a href="#" class="underline">Terms of Use</a>.
        </small>
        
    </div>
</template>