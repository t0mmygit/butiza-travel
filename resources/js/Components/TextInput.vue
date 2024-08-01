<script setup>
import { ref, computed } from 'vue';
import Textarea from 'primevue/textarea';
import InputText from 'primevue/inputtext';
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
        default: false,
    },
    name: {
        type: String
    },
    icon: {
        type: String,
    }
});

const selectedCountry = ref({
    country: 'Malaysia',
    code: '+60'
});

const countries = ref([
    { name: 'Malaysia', code: '+60' }
]);

const input = ref(null);

const isFocused = ref(false);

const classes = computed(() => 
    props.error
    ? 'border-red-error rounded-md disabled:bg-neutral transition-none'
    : 'border-neutral-300 border rounded-md disabled:bg-neutral transition-none'
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
            :name="name"
            :invalid="error ? true : false"
            :rows="5"
        />
        <InputGroup v-else-if="type === 'tel'">
            <InputGroupAddon class="m-0 p-0">
                <!-- Still in development -->
                <!-- Dropdown for country selection -->
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
            <InputText 
                v-model="model"
                :name="name"
                :placeholder="placeholder"
                :invalid="error ? true : false"
                :disabled="disabled"
            />
        </InputGroup>
        <InputGroup v-else>
            <InputGroupAddon v-if="icon" ref="input" :class="{'border-primary': isFocused}">
                <i :class="icon"></i>
            </InputGroupAddon>
            <InputText 
                v-model="model"
                :type="type"
                :name="name"
                :placeholder="placeholder"
                :class="[classes, { 'rounded-l-none': icon }]"
                :invalid="error ? true : false"
                :disabled="disabled"
                @focus="isFocused = true"
                @blur="isFocused = false"
            />    
        </InputGroup>
        <p class="text-error text-sm mt-2">{{ error }}</p>
    </div>
</template>
