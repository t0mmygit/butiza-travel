<script setup>
import Button from 'primevue/button';
import TextInput from '@/Components/TextInput.vue';
import PhoneInput from '@/Components/PhoneInput.vue';
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
    phone_number: user.phone_number,
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
            <div class="flex flex-col">
                <TextInput
                    v-model="user.email"
                    disabled
                />
                <div v-if="mustVerifyEmail && user.email_verified_at === null">
                    <p class="text-sm mt-2">
                        Your email address is unverified.
                        <Link
                            :href="route('verification.send')"
                            method="post"
                            as="button"
                            class="underline text-link"
                        >
                            Click here to re-send the verification email.
                        </Link>
                    </p>
    
                    <div
                        v-show="status === 'verification-link-sent'"
                        class="mt-2 font-medium text-sm text-success"
                    >
                        A new verification link has been sent to your email address.
                    </div>
                </div>
            </div>


            <div>
                <TextInput
                    v-model="form.first_name"
                    label="First Name"
                    :error="form.errors.first_name"
                    required
                />
            </div>

            <div>
                <TextInput
                    v-model="form.last_name"
                    label="Last Name"
                    :error="form.errors.last_name"
                    required
                />
            </div>

            <div>
                <PhoneInput
                    v-model="form.phone_number"
                    label="Phone Number"
                    :error="form.errors.phone_number"
                />
            </div>
k
            <div class="flex items-center gap-4">
                <Button 
                    label="Save"
                    type="submit"
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
