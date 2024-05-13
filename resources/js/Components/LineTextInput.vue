<script setup>
import { onMounted, ref } from 'vue';

const model = defineModel({
    type: String,
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
    <input
        class="
            border-gray-400 
            bg-slate-200
            focus:border-indigo-300
            border-x-0
            border-t-0
            border-b-2
            rounded"
        v-model="model"
        ref="input"
    />
</template>
