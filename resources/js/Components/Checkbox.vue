<script setup>
import { computed } from 'vue';

const emit = defineEmits(['update:checked']);

const props = defineProps({
    checked: {
        type: [Array, Boolean],
    },
    value: {
        default: null,
    },
    label: {
        type: String,
        required: true
    },
    disabled: {
        type: Boolean,
    }
});

const proxyChecked = computed({
    get() {
        // 
    },

    set(val) {
        if (val) emit('update:checked', props.value);
        else emit('update:checked', val);
    },
});

const classes = computed(() => 
    props.disabled 
        ? 'whitespace-nowrap ml-3 text-neutral-300 text-sm'
        : 'whitespace-nowrap ml-3 text-neutral-500 text-sm'
);


</script>

<template>
    <label for="value" class="flex mr-4 items-center flex-nowrap">
        <input
            type="checkbox"
            :value="value"
            v-model="proxyChecked"
            :disabled="disabled"
            class="rounded border-gray-300 text-primary-600 shadow-sm focus:ring-primary-500"
        />
        <span :class="classes">{{ label }}</span>
    </label>
</template>
