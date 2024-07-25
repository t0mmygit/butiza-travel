<script setup>
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';

import Button from 'primevue/button';
import Divider from 'primevue/divider';

const props = defineProps({
    user: {
        type: Object,
        required: true,
    }
});

const form = useForm({
    first_name: props.user.first_name,
    last_name: props.user.last_name,
    email: props.user.email,
    phone_number: props.user.phone_number ?? null,
});

</script>

<template>
    <div class="flex flex-col gap-8">
        <div class="border border-neutral-300 rounded-md p-4">
            <main>
                <h2>Personal Information</h2>
                <p class="mt-1 text-sm text-neutral-600">
                    Update your personal account's information. 
                </p>
                <Divider />
                <form>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <TextInput
                            v-model="form.first_name"
                            label="First Name"
                        />
                        <TextInput
                            v-model="form.last_name"
                            label="Last Name"
                        />
                        <TextInput
                            v-model="form.email"
                            label="Email Address"
                        />
                        <TextInput
                            v-model="form.phone_number"
                            type="tel"
                            label="Phone Number"
                        />
                    </div>
                    <div class="flex justify-end">
                        <Button label="Save Changes" class="mt-4" />
                    </div>
                </form>
            </main>
        </div>
    </div>
</template>