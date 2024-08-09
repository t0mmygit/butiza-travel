<script setup>
import Button from 'primevue/button';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;

const form = useForm({
    first_name: user.first_name,
    last_name: user.last_name,
    email: user.email,
});
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">Profile Information</h2>

            <p class="mt-1 text-sm text-gray-600">
                Update your account's profile information and email address.
            </p>
        </header>

        <form @submit.prevent="form.patch(route('profile.update'))" class="mt-6 space-y-6">
            <div>
                <TextInput
                    id="firstName"
                    v-model="form.first_name"
                    label="First Name"
                    :error="form.errors.first_name"
                    required
                    class="mt-1 block w-full"
                />
            </div>

            <div>
                <TextInput
                    id="name"
                    v-model="form.last_name"
                    label="Last Name"
                    :error="form.errors.last_name"
                    required
                    class="mt-1 block w-full"
                />
            </div>

            <div class="flex items-center gap-4">
                <Button 
                    label="Save"
                    :disabled="form.processing"
                />

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Saved.</p>
                </Transition>
            </div>
        </form>
    </section>
</template>
