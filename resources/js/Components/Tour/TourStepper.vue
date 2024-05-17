<script setup>
defineProps({
    days: {
        type: Object,
        required: true
    }
})
</script>

<template>
    <div v-for="day in days">
        <div class="pb-6">
            <div class="flex items-center">
                <div class="bg-primary size-4 mr-6"></div>
                <h4>Day {{ day.day_number }} {{ day.day_title }}</h4>
            </div>
            <div class="flex h-fit">
                <div id="line" class="bg-primary ml-1.5 mr-8 w-1 max-w-1 grow rounded-full"></div>
                <p>{{ day.description }}</p>
            </div>
        </div>
    </div>
</template>