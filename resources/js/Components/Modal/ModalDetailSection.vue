<script setup>
import TextInput from '@/Components/TextInput.vue';
import PasswordInput from '../PasswordInput.vue';
import Button from 'primevue/button';
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
    first_name: null,
    last_name: null,
    password: null,
    password_confirmation: null
});

const submitDetail = () => {
    form.post(route('register'), {
        onSuccess: () => emit('close'),
        onFinish: () => resetForm(),
    })
};

function resetForm() {
    return form.reset([
        'first_name',
        'last_name',
        'password',
        'password_confirmation'
    ]);
}

</script>

<template>
    <div class="flex flex-col justify-center items-center p-6 mb-6">
        <div class="flex lg:justify-center lg:col-start-2 mb-4">
            <ApplicationLogo />
        </div>
        <h2 class="font-bold text-2xl mb-2">Join us. It's free.</h2>
        <p class="mb-8">Access member savings & community.</p>
        <form @submit.prevent="submitDetail">
            <div class="flex flex-col mb-4">
                <TextInput
                    v-model="form.first_name"
                    label="First Name"
                    :error="form.errors.first_name"
                    required
                />
                <TextInput
                    v-model="form.last_name"
                    label="Last Name"
                    :error="form.errors.last_name"
                    required
                />
                <PasswordInput
                    v-model="form.password"
                    label="Password"
                    :error="form.errors.password"
                    required
                />
                <PasswordInput
                    v-model="form.password_confirmation"
                    label="Confirm Password"
                    :error="form.errors.password_confirmation"
                    required
                />
            </div>
            <Button 
                label="Join"
                type="submit"
                :disabled="form.processing"
                class="w-full"
            />
        </form>
        <div class="flex flex-col mt-4">
            <a
                @click="$emit('previous')"
                class="text-secondary text-center text-sm underline cursor-pointer"
            >
                Sign Up with Facebook or Google instead?
            </a>
            <small class="text-gray-500 text-center mt-8">
                By signing in, you are agreeing to our <br>
                <a href="#" class="underline">Privacy Policy</a>, and
                <a href="#" class="underline">Terms of Use</a>.
            </small>
        </div>
    </div>
</template>