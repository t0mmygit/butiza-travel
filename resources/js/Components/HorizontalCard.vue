<script setup>
import Button from '@/Components/PrimaryButton.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'
import { router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps({
    tour: {
        type: Object
    },
    items: {
        type: Array
    },
    mode: {
        type: Boolean
    }
});

const emit = defineEmits(['compare-tour', 'host-tour'])

const activeCompareButton = ref(false);

const viewTour = () => {
    try {
        router.get(route('tour.show', {
            id: props.tour.id
        }));
    } catch (error) {
        console.error('Error viewing tours:', error);
    }
}

const formatPrice = (price) => {
    var priceToString = price.toString();
    var formattedPrice = priceToString.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    return 'RM' + formattedPrice;
};

const formatPricePerDay = (price) => {
    price = Math.ceil(price / props.tour.day);
    return formatPrice(price);
};

const itemsListener = computed(() => {
    let index = props.items.findIndex(item => item.id === props.tour.id);
    return index != -1 ?  true : false;
});

const formatDestination = destinations => destinations.map(destination => destination.destination_name).join(', ');

</script>

<template>
    <div v-if="tour" class="mb-4">
        <div class="bg-white rounded-lg shadow flex p-3 justify-between gap-4 items-start max-w-full">

            <!-- Card Image -->
            <div id="image" class="aspect-square flex-initial min-w-60 max-w-60">
                <img 
                    class="rounded" 
                    src="https://keycdn.borneoecotours.com/images/cache/tours/cover/bb07a-1000x1000.webp"
                >
            </div>

            <!-- Card Details -->
            <div class="mr-auto">
                <h1 class="mb-2">{{ tour.name }}</h1>
                <div class="font-medium text-gray-500 mb-4">
                    <span>No Reviews</span>
                </div>

                <div class="grid grid-cols-3 mb-4">
                    <strong>Destinations</strong>
                    <span class="truncate col-start-2 col-span-3">{{ formatDestination(tour.destinations) }}</span>
                    <strong>Age Range</strong>
                    <span class="col-start-2 col-span-3">up to {{ tour.max_age }} years olds</span>
                </div>

                <div id="additional" class="flex gap-10 pr-4">
                    <div>
                        <h3 class="font-bold">Travel Intensity</h3>
                        <span>{{ tour.travel_intensity }}</span>
                    </div>
                    <div>
                        <h3 class="font-bold">Lodging Level</h3>
                        <span>Excellent</span>
                    </div>
                    <div>
                        <h3 class="font-bold">Durations</h3>
                        <span v-if="tour.day > 1">{{ tour.day }} Days</span>
                        <span v-else-if="tour.day = 1">{{ tour.day }} Day</span>
                    </div>
                </div>
            </div>

            <!-- Card Price -->
            <div class="grid grid-rows-2 min-w-max">
                <div class="flex flex-col">
                    <span>From</span>
                    <strong class="text-2xl">{{ formatPrice(tour.base_price) }}</strong>
                    <span>Price per day</span>
                    <strong>{{ formatPricePerDay(tour.base_price) }}</strong>
                </div>
                <div class="flex flex-col place-self-end gap-2">
                    <Button @click="viewTour">
                        View Tour
                    </Button>
                    <SecondaryButton v-if="!mode" @click="$emit('compare-tour', tour)" :active="itemsListener">
                        <span>Compare</span>
                    </SecondaryButton>
                    <SecondaryButton v-else @click="$emit('host-tour', tour)">
                        <span>Host Tour</span>
                    </SecondaryButton>
                </div>
            </div>

        </div>
    </div>

    <!-- Empty Tours -->
    <div v-else>
        Nothing to see here...
    </div>
</template>