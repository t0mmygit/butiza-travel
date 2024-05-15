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
    }
});

const proxyChecked = computed({
    get() {
        return props.checked;
    },

    set(val) {
        emit('update:checked', val);
    },
});
</script>

<template>
    <label for="value" class="flex mr-4 items-center flex-nowrap">
        <input
            type="checkbox"
            :value="value"
            v-model="proxyChecked"
            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
        />
        <span class="whitespace-nowrap ml-6">{{ label }}</span>
    </label>
</template>
