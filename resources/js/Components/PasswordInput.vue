<script setup>
import Password from 'primevue/password';

const model = defineModel({
    type: String,
});

const props = defineProps({
    label: {
        type: String,
    },
    name: {
        type: String,
    },
    placeholder: {
        type: String
    },
    error: {
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
});

</script>

<template>
    <div class="flex flex-col flex-1">
        <label v-if="label" class="text-neutral-500 text-sm mb-2">{{ label }}
            <span v-if="required" class="text-error">*</span>
        </label>
        <Password
            v-model="model"
            :name="name"
            :placeholder="placeholder"
            :disabled="disabled"
            :required="required"
            :feedback=false
        />
        <p class="text-error text-sm mt-2">{{ error }}</p>
    </div>
</template>