<script setup>
import { onMounted, ref, computed } from 'vue';

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
const classes = computed(() => 
    props.error
    ? 'border-red-400 focus:border-none rounded-md disabled:bg-neutral'
    : 'border-neutral-300 border focus:border-primary-100 rounded-md disabled:bg-neutral'
);

// onMounted(() => {
//     if (input.value.hasAttribute('autofocus')) {
//         input.value.focus();
//     }
// });

// defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <div class="flex flex-col flex-1">    
        <label v-if="label" class="text-neutral-500 text-sm mb-1 ml-2">{{ label }}</label>
        <input
            :type="type"
            :placeholder="placeholder"
            :class="classes"
            v-model="model"
            :disabled="disabled"
        />
        <p class="text-error text-sm mt-2">{{ error }}</p>
    </div>
</template>
