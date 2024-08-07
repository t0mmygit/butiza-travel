<script setup>
import InputNumber from 'primevue/inputnumber';

const model = defineModel({
    type: [Number, String],
});

const props = defineProps({
    label: {
        type: String,
    },
    name: {
        type: String,
    },
    placeholder: {
        type: String,
    },
    error: {
        type: [String, undefined],
    },
    required: {
        type: Boolean,
    },
    useGrouping: {
        type: Boolean,
        default: true,
    },
    disabled: {
        type: Boolean,
        default: false,
    },
});

</script>

<template>
    <div class="flex flex-col flex-1">
        <label v-if="label" class="text-neutral-500 text-sm mb-2">
            {{ label }}
            <span v-if="required" class="text-error">*</span>
        </label> 
        <InputNumber
            v-model="model"
            :name="name"
            :placeholder="placeholder"
            :invalid="error ? true : false"
            :disabled="disabled"
            :useGrouping="useGrouping"
            :required="required"
        />
    </div>
</template>