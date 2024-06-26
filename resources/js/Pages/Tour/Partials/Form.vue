<script setup>

import Divider from 'primevue/divider';

defineProps({
    index: {
        type: Number,
        required: true,
    },
    title: {
        type: String,
        required: true,
    },
});

</script>

<template>
    <div class="bg-white flex flex-col p-5 rounded-lg shadow mb-6">
        <div class="flex items-center gap-3">
            <div class="flex items-center justify-center text-white bg-primary size-8 rounded-md">
                {{ index }}
            </div>
            <h1>{{ title }}</h1>
        </div>
        <Divider />
        <slot name="subtitle" />
        <div class="flex flex-col gap-3">
            <slot />
        </div>
    </div>
</template>