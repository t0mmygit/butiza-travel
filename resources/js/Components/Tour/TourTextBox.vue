<script setup>
defineProps({
    label: {
        type: String,
        required: true
    },
    values: {
        type: [Array, Object],
        required: true
    }
})
</script>

<template>
    <div class="mt-8">
        <h2 class="mb-2">{{ label }}</h2>
        <div class="flex gap-4">
            <div v-for="value in values" class="px-4 py-2 border border-neutral-300 rounded max-w-fit">
                {{ value.destination_name }}
            </div>
        </div>
    </div>
</template>