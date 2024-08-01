<script setup>
import Button from 'primevue/button';
import InputError from '@/Components/InputError.vue';
import Password from 'primevue/password';
import { useForm } from '@inertiajs/vue3';
import { onMounted } from 'vue';

const emit = defineEmits(['close']);

const props = defineProps({
    email: {
        type: String,
        required: true
    }
});

const form = useForm({
    email: null,
    password: null,
});

onMounted(() => {
    form.email = props.email;
});

const submitPassword = () => {
    form.post(route('login'), {
        onSuccess: () => emit('close'),
        onError: (error) => console.error(error),
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
            <div class="mb-4">
                <Password 
                    v-model="form.password" 
                    placeholder="Password" 
                    :feedback="false" toggleMask 
                    :invalid="form.errors.password != null ? true : false"
                />
                <InputError :message="form.errors.password || form.errors.email" class="mt-2" />
            </div>
            <Button
                type="submit"
                label="Confirm"
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