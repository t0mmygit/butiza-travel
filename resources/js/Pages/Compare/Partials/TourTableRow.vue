<script setup>
import { useFormatPrice } from '@/Composables/formatPrice.js'
import { computed } from 'vue';

import SecondaryButton from '@/Components/SecondaryButton.vue';

const props = defineProps({
    tours: {
        type: [Array, Object],
        required: true,
    }
});

const rows = computed(() => [
    {
        title: 'Duration',
        value: (tour) => `${tour.day} Days`,
    },
    {
        title: 'Ratings & Reviews',
        value: (tour) => 'No reviews yet',
    },
    {
        title: 'Age Range',
        value: (tour) => `${tour.min_age} - ${tour.max_age} years old`,
    },
    {
        title: 'Destinations',
        value: (tour) => formatDestination(tour.destinations),
    },
    {
        title: 'Travel Intensity',
        value: (tour) => tour.travel_intensity,
    },
    {
        title: '',
        value: (tour) => `<span>from</span><strong class="text-2xl ml-2">${useFormatPrice(tour.base_price)}</strong>`,
    }
]);

function rowClass(index) {
    return {
        'h-10 bg-neutral': index % 2 === 0,
    };
}

const formatDestination = destinations => destinations.map(destination => destination.name).join(', ');

</script>

<template>
    <tr v-for="(row, index) in rows" :key="index" :class="rowClass(index)">
        <th>{{ row.title }}</th>
        <td v-for="tour in tours" class="p-2">
            <div v-html="row.value(tour)"></div>
        </td>
    </tr>
    <tr>
        <th></th>
        <td v-for="tour in tours" :key="tour.id" class="p-2">
            <SecondaryButton @click="route('tour.show', { tour: tour.id })" class="w-full rounded-xl text-indigo-800 bg-indigo-100">
                View Tour
            </SecondaryButton>
        </td>
    </tr>
</template>