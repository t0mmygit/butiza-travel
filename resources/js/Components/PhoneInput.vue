<script setup>
import InputGroup from 'primevue/inputgroup';
import InputGroupAddon from 'primevue/inputgroupaddon';
import Dropdown from 'primevue/dropdown';
import InputNumber from 'primevue/inputnumber';
import InputText from 'primevue/inputtext';
import { ref } from 'vue';

const model = defineModel({
    type: String,
});

const props = defineProps({
    label: {
        type: String,
        required: true
    },
    name: {
        type: String,
    },
    error: {
        type: [String, null],
    },
    placeholder: {
        type: String,
    },
    disabled: {
        type: Boolean,
        default: false
    },
    required: {
        type: Boolean,
        default: false,
    },
    icon: {
        type: String,
    },
    useGrouping: {
        type: Boolean,
    },
});

const selectedCountry = ref({
    country: 'Malaysia',
    code: '+60'
});

const countries = ref([
    { name: 'Malaysia', code: '+60' }
]);

</script>

<template>
    <div class="flex flex-col flex-1">
        <label v-if="label" class="text-neutral-500 text-sm mb-2">{{ label }}
            <span v-if="required" class="text-error">*</span>
        </label>
        <InputGroup class="group">
            <InputGroupAddon class="m-0 p-0">
                <!-- Still in development -->
                <!-- Dropdown for country selection -->
                 <!-- code is not stored -->
                <Dropdown
                    v-model="selectedCountry"
                    :options="countries"
                    optionLabel="code"
                    placeholder="Select a Country"
                    :disabled="disabled"
                    pt:root:class="border-none shadow-none rounded-r-none"
                    pt:trigger:class="hidden"
                >
                    <template #value="slotProps">
                        <div v-if="slotProps.value">
                            <div>{{ slotProps.value.code }}</div>
                        </div>
                        <div v-else>
                            {{ slotProps.placeholder }}
                        </div>
                    </template>
                    <template #option="slotProps">
                        <div>{{ slotProps.option.name }} ({{ slotProps.option.code }})</div>
                    </template>
                </Dropdown>
            </InputGroupAddon>
            <InputText
                v-model="model"
                :name="name"
                :placeholder="placeholder"
                :invalid="error ? true : false"
                :disabled="disabled"
                :required="required"
                onkeypress="return /[0-9]/i.test(event.key)"
            />
        </InputGroup>
        <p class="text-error text-sm mt-2">{{ error }}</p>
    </div>
</template>