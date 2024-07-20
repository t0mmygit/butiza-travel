<script setup>
const props = defineProps({
    days: {
        type: Object,
        required: true
    }
});
</script>

<template>
    <div class="flex flex-col gap-4">
        <div v-for="day in days">
            <div class="flex items-center">
                <i class="pi pi-map-marker text-primary text-lg mr-6"></i>
                <span class="text-black font-medium">Day {{ day.day_number }} {{ day.day_title }}</span>
            </div>
            <div class="flex h-fit">
                <div class="bg-primary ml-2 mr-8 w-1 max-w-0.5 grow rounded-full"></div>
                <p>{{ day.description }}</p>
            </div>
        </div>
    </div>
</template>