<script setup>
import { onMounted, ref } from 'vue';

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
    }
});

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <div class="flex flex-col flex-1">    
        <label v-if="label" class="text-neutral-500 text-sm mb-1 ml-2">{{ label }}</label>
        <input
            :type="type"
            :placeholder="placeholder"
            class="border-neutral-300 border focus:border-primary-100, rounded-md shadow disabled:bg-neutral"
            v-model="model"
            ref="input"
            :disabled="disabled"
        />
        <p class="text-error text-sm mt-2">{{ error }}</p>
    </div>
</template>
