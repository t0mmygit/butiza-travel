<script setup>
import NavBar from '@/Components/NavBar.vue';
import { router } from '@inertiajs/vue3';
import TourTableRow from '@/Pages/Compare/Partials/TourTableRow.vue';

const props = defineProps({
    tours: {
        type: [Array, Object],
        required: true
    }
});

const formatPrice = (price) => {
    var priceToString = price.toString();
    var formattedPrice = priceToString.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    return 'RM' + formattedPrice;
}

const view = (tourId) => {
    try {
        router.get(route('tour.show', { tour: tourId } ))
    } catch (error) {
        console.error('Error in viewing tour:', error)
    }
};

const formatDestination = destinations => destinations.map(destination => destination.name).join(', ');
</script>


<template>
    <NavBar />

    <header class="mt-2">
        <div id="heading-container" class="bg-white font-sans py-4">
            <div id="heading" class="m-auto lg:w-5/6 xl:w-10/12 2xl:w-7/12">
                <h1 class="text-2xl font-black">Compare Tours</h1>
            </div>
        </div>
    </header>
    
    <section class="m-auto lg:w-5/6 xl:w-10/12 2xl:w-7/12 overflow-x-auto">
        <div id="table-container" class="text-left text-lg">
            <table class="table-fixed border-separate border-spacing-y-2 w-full">
                <thead class="p-2">
                    <tr>
                        <th></th>
                        <th v-for="tour in tours" class="hover:cursor-pointer p-2">
                            <img class="rounded-xl mb-4 aspect-video object-cover" src="https://keycdn.borneoecotours.com/images/cache/tours/cover/bb07a-1000x1000.webp">
                            <h2 class="text-xl line-clamp-2 hover:underline" @click="view(tour.id)">{{ tour.name }}</h2>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <!-- <tr class="h-10 bg-neutral">
                        <th>Duration</th>
                        <td v-for="tour in tours" class="p-2">{{ tour.day }} Days</td>
                    </tr>
                    <tr>
                        <th>Ratings & Reviews</th>
                        <td v-for="tour in tours" class="p-2">No reviews yet</td>
                    </tr>
                    <tr class="h-10 bg-neutral">
                        <th>Age Range</th>
                        <td v-for="tour in tours" class="p-2">{{ tour.min_age }} - {{ tour.max_age }} years old</td>
                    </tr>
                    <tr>
                        <th>Destinations</th>
                        <td v-for="tour in tours" class="p-2">
                            {{ formatDestination(tour.destinations) }}
                        </td>
                    </tr>
                    <tr class="h-10 bg-neutral">
                        <th>Travel Intensity</th>
                        <td v-for="tour in tours" class="p-2">{{ tour.travel_intensity }}</td>
                    </tr>
                    <tr>
                        <th></th>
                        <td v-for="tour in tours" class="p-2">
                            <span>from</span>
                            <strong class="text-2xl ml-2">{{ formatPrice(tour.base_price) }}</strong>
                        </td>
                    </tr>
                    <tr>
                        <th></th>
                        <td v-for="tour in tours" class="p-2">
                            <SecondaryButton @click="view(tour.id)" class="w-full rounded-xl text-indigo-800 bg-indigo-100">
                                View Tour
                            </SecondaryButton>
                        </td>
                    </tr> -->
                    <TourTableRow :tours="tours" />
                </tbody>
            </table>
        </div>
    </section>
</template>