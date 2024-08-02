<script setup>
import Dropdown from 'primevue/dropdown';
import Divider from 'primevue/divider';
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

const form = useForm({
    contact_method_id: props.partner.settings?.contact_method_id
});

const saveChanges = () => {
    // TODO: Route
};

</script>

<template>
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
        </form>
    </div>
</template>