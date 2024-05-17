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
    }
});

const emit = defineEmits(['compare-tour'])

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
})

</script>

<template>
    <div v-if="tour" class="mb-4">
        <div class="bg-white flex h-fit p-3 rounded-xl shadow">

            <!-- Card Image -->
            <div id="image" class="aspect-square max-h-96">
                <img 
                    class="object-contain rounded-md" 
                    src="https://keycdn.borneoecotours.com/images/cache/tours/cover/bb07a-1000x1000.webp"
                >
            </div>

            <!-- Card Details -->
            <div id="text" class="ml-4">
                <h3 class="text-2xl font-black mb-2">{{ tour.name }}</h3>
                <div id="review" class="font-medium text-gray-500 mb-4">
                    <span>No Reviews</span>
                </div>
                <div id="details" class="flex mb-4">
                    <div class="flex flex-col font-bold pr-8">
                        <span>Destinations</span>
                        <span>Age Range</span>
                    </div>
                    <div>
                        <div id="destination" class="flex gap-2">
                            <span>{{ tour.destination }}</span>
                        </div>
                        <div>up to 95 years olds</div>
                    </div>
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
                        <span>{{ tour.day }} Days</span>
                    </div>
                </div>
            </div>

            <!-- Card Price -->
            <div id="right-section" class="flex flex-col justify-between pr-2">
                <div id="price">
                    <div class="mb-3 flex flex-col">
                        <span>From</span>
                        <strong class="text-2xl">{{ formatPrice(tour.base_price) }}</strong>
                    </div>
                    <div id="person" class="flex flex-col">
                        <span>Price Per Day</span>
                        <strong>{{ formatPricePerDay(tour.base_price) }}</strong>
                    </div>  
                </div>
                <div class="mb-2 mr-2">
                    <Button @click="viewTour" class="mb-2 w-full">
                        View Tour
                    </Button>
                    <SecondaryButton @click="$emit('compare-tour', tour)" :active="itemsListener">
                        <span>Compare</span>
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