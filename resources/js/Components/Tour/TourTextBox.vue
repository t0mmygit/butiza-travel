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
    <div class="flex flex-col gap-2">
        <h2>{{ label }}</h2>
        <div class="flex flex-wrap gap-4">
            <div v-for="valueObject in value" class="px-4 py-2 border border-neutral-300 rounded max-w-fit">
                {{ valueObject.name }}
            </div>
        </div>
    </div>
</template>