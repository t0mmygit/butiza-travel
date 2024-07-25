<script setup>
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';

import Button from 'primevue/button';
import Divider from 'primevue/divider';
import FileUpload from 'primevue/fileupload';
import InputError from '@/Components/InputError.vue';
import Toast from 'primevue/toast';
import { useToast } from 'primevue/usetoast';

const toast = useToast();

import { computed } from 'vue';

const props = defineProps({
    user: {
        type: Object,
        required: true,
    }
});

const form = useForm({
    business_name: props.user.partner.business_name,
    website: props.user.partner.website,
    country: props.user.partner.country,
    city: props.user.partner.city,
    registration_number: props.user.partner.registration_number ?? '',
    ssm_path: props.user.partner.ssm_path ?? '',
});

const saveChanges = () => {
    form.patch(route('partner-account.update', { partner: props.user.partner.id }), {
        preserveScroll: true,
        onSuccess: () => {
            toast.add({ severity: 'success', summary: 'Success', detail: 'Company information updated', life: 3000 });
            form.clearErrors();
        },
        onError: (error) => console.error(error),
    });
}

const formChanged = computed(() => {
    return (
        form.business_name !== props.user.partner.business_name ||
        form.website !== props.user.partner.website ||
        form.country !== props.user.partner.country ||
        form.city !== props.user.partner.city ||
        form.registration_number !== (props.user.partner.registration_number ?? '') ||
        form.ssm_path !== (props.user.partner.ssm_path ?? '')
    );
});

</script>

<template>
    <Toast />
    <div class="flex flex-col gap-6">
        <div class="border border-neutral-300 rounded-md p-4">
            <div>
                <h2>Company Information</h2>
                <p class="mt-1 text-sm text-neutral-600">
                    Update your company's information. 
                </p>
                <Divider />
                <form @submit.prevent="saveChanges">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <TextInput
                            v-model="form.business_name"
                            label="Business Name"
                            :error="form.errors.business_name"
                            required
                        />
                        <TextInput
                            v-model="form.website"
                            label="Website"
                            :error="form.errors.website"
                            required
                        />
                        <TextInput
                            v-model="form.country"
                            label="Country"
                            :error="form.errors.country"
                            required
                        />
                        <TextInput
                            v-model="form.city"
                            label="City"
                            :error="form.errors.city"
                            required
                        />
                        <div class="col-span-2 flex flex-col gap-4">
                            <div class="flex flex-col gap-2">
                                <TextInput 
                                    v-model="form.registration_number"
                                    label="Registration Number"
                                    :error="form.errors.registration_number"
                                    :disabled="form.registration_number"
                                />
                                <label class="text-neutral-500 text-sm">SSM</label>
                                <FileUpload>
                                    <template #empty>
                                        <p>Drag and drop files here to upload.</p>
                                    </template>
                                </FileUpload>
                                <InputError :message="form.errors.ssm_path" />
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <Button 
                            label="Save Changes"
                            type="submit"
                            class="mt-4"
                            :disabled="!formChanged"
                        />
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>