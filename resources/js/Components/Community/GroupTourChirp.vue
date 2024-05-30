<script setup>
import RoundedButton from '@/Components/RoundedButton.vue';

const props = defineProps({
    post: {
        type: [Array, Object],
        required: true
    },
    image: {
        type: String,
        required: true
    },
});

</script>

<template>
    <div class="flex flex-col bg-white rounded-md border border-neutral-300 shadow-sm mb-2">
        <div class="grid grid-cols-2 border-b border-b-neutral-300">
            <img
                :src="image"
                class="rounded-ss-md h-full aspect-video"
            >
            <div class="px-3 py-2 mb-2">
                <h2>{{ post.tour.name }}</h2>
                <p class="text-justify text-clip">{{ post.tour.description }}</p>
            </div>
        </div>
        <div class="flex items-center px-3 py-2 justify-between">
            <div>
                <div class="flex items-center gap-1">
                    <i class="pi pi-users"></i>
                    <h3>3/{{ post.max_passenger }}</h3>
                </div>
            </div>
            <RoundedButton>View Group Tour</RoundedButton>
        </div>
    </div>
</template>