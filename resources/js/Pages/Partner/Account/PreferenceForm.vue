<script setup>
import Button from 'primevue/button';
import Dropdown from 'primevue/dropdown';
import Divider from 'primevue/divider';
import Toast from 'primevue/toast';
import { useToast } from 'primevue/usetoast';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    partner: {
        type: Object,
        required: true,
    },
    contact_methods: {
        type: Object,
        required: true,
    }
});

const toast = useToast();

const form = useForm({
    contact_method_id: props.partner.settings?.contact_method_id
});

const saveChanges = () => {
    form.patch(route('partner-preference.update', { partner: props.partner.id }), {
        onSuccess: () => {
            toast.add({ severity: 'success', summary: 'Success', detail: 'Preference updated', life: 3000 });
        },
        onError: (error) => {
            toast.add({ severity: 'error', summary: 'Error', detail: 'Failed to update preference!', life: 3000 });
            console.error(error);
        }
    });
};

</script>

<template>
    <Toast />
    <div class="border border-neutral-300 rounded-md p-4">
        <h1>Preference</h1>
        <Divider />
        <form @submit.prevent="saveChanges">
            <div class="flex flex-col">
                <label class="text-neutral-500 mb-2 text-sm">Method of Contact</label>
                <Dropdown
                    v-model="form.contact_method_id"
                    :options="contact_methods"
                    optionLabel="method_name"
                    optionValue="id"
                />
            </div>
            <div class="flex justify-end mt-3">
                <Button 
                    label="Save Changes"
                    type="submit"
                    :disabled="!form.isDirty || form.processing"
                />
            </div>
        </form>
    </div>
</template>