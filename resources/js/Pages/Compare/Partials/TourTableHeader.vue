<script setup>

const props = defineProps({
    tours: {
        type: [Array, Object],
        required: true,
    },
    label: {
        type: String,
    },
});

</script>

<template>
     <tr>
        <th>{{ label }}</th>
        <th v-for="tour in tours" class="hover:cursor-pointer p-2">
            <img class="rounded-xl mb-4 aspect-video object-cover" src="https://keycdn.borneoecotours.com/images/cache/tours/cover/bb07a-1000x1000.webp">
            <h2 class="text-xl line-clamp-2 hover:underline" @click="route('tour.show', { tour: tourId })">{{ tour.name }}</h2>
        </th>
    </tr>
</template>