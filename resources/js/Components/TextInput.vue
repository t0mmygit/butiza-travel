<script setup>
import { onMounted, ref, computed } from 'vue';
import Textarea from 'primevue/textarea';
import InputText from 'primevue/inputtext';
import InputNumber from 'primevue/inputnumber';
import InputGroup from 'primevue/inputgroup';
import InputGroupAddon from 'primevue/inputgroupaddon';
import Dropdown from 'primevue/dropdown';

const model = defineModel({
    type: [String, Number],
});

const props = defineProps({
    type: {
        type: String,
        default: 'text'
    },
    label: {
        type: String
    },
    error: {
        type: String
    },
    placeholder: {
        type: String
    },
    disabled: {
        type: Boolean,
        default: false
    },
    required: {
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

const input = ref(null);

const classes = computed(() => 
    props.error
    ? 'border-red-error rounded-md disabled:bg-neutral'
    : 'border-neutral-300 border rounded-md disabled:bg-neutral'
);

</script>

<template>
    <div class="flex flex-col flex-1">    
        <label v-if="label" class="text-neutral-500 text-sm mb-2">{{ label }}
            <span v-if="required" class="text-error">*</span>
        </label>
        <Textarea 
            v-if="type === 'textarea'" 
            v-model="model" 
            :placeholder="placeholder"
            :disabled="disabled"
            :invalid="error ? true : false"
            :rows="5"
        />
        <InputGroup v-else-if="type === 'tel'">
            <InputGroupAddon class="m-0 p-0">
                <!-- Still in development -->
                <Dropdown 
                    v-model="selectedCountry" 
                    :options="countries" 
                    optionLabel="code"
                    placeholder="Select a Country"
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
            <InputNumber 
                v-model="model"
                :placeholder="placeholder"
                :invalid="error ? true : false"
                :useGrouping="false"
            />
        </InputGroup>
        <InputText 
            v-else
            v-model="model"
            :type="type"
            :placeholder="placeholder"
            :class="classes"
            :invalid="error ? true : false"
            :disabled="disabled"
        />
        <p class="text-error text-sm mt-2">{{ error }}</p>
    </div>
</template>
