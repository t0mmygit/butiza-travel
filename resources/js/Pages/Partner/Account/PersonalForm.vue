<script setup>
import TextInput from '@/Components/TextInput.vue';
import PhoneInput from '@/Components/PhoneInput.vue';
import { useForm } from '@inertiajs/vue3';

import Button from 'primevue/button';
import Divider from 'primevue/divider';
import Toast from 'primevue/toast';
import { useToast } from 'primevue/usetoast';

const props = defineProps({
    user: {
        type: Object,
        required: true,
    }
});

const toast = useToast();

const form = useForm({
    first_name: props.user.first_name,
    last_name: props.user.last_name,
    email: props.user.email,
    phone_number: props.user.phone_number ?? '',
});

const saveChanges = () => {
    form.patch(route('user.update', { user: props.user.id }), {
        preserveScroll: true,
        onSuccess: () => {
            toast.add({ severity: 'success', summary: 'Success', detail: 'Personal information updated', life: 3000 });
            form.clearErrors();
        },
        onFinish: () => form.reset(),
    });
};

</script>

<template>
    <Toast />
    <div class="flex flex-col gap-8">
        <div class="border border-neutral-300 rounded-md p-4">
            <main>
                <h2>Personal Information</h2>
                <p class="mt-1 text-sm text-neutral-600">
                    Update your personal account's information. 
                </p>
                <Divider />
                <form @submit.prevent="saveChanges">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                        <TextInput
                            v-model="form.email"
                            label="Email Address"
                            :error="form.errors.email"
                            required
                        />
                        <PhoneInput
                            v-model="form.phone_number"
                            label="Phone Number"
                            :error="form.errors?.phone_number"
                            :useGrouping="false"
                            required
                        />
                    </div>
                    <div class="flex justify-end">
                        <Button 
                            label="Save Changes" 
                            type="submit"
                            class="mt-4"
                            :disabled="!form.isDirty || form.processing"
                        />
                    </div>
                </form>
            </main>
        </div>
    </div>
</template>