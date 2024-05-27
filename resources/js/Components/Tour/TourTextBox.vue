<script setup>
defineProps({
    label: {
        type: String,
        required: true
    },
    value: {
        type: [Array, Object],
        required: true
    }
})
</script>

<template>
    <div class="mt-8">
        <h2 class="mb-2">{{ label }}</h2>
        <div class="flex gap-4">
            <div v-for="valueObject in value" class="px-4 py-2 border border-neutral-300 rounded max-w-fit">
                {{ valueObject.name }}
            </div>
        </div>
    </div>
</template>