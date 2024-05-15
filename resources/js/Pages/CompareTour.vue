<script setup>
import NavBar from '@/Components/NavBar.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { router } from '@inertiajs/vue3';

const props = defineProps(['tours'])

const formatPrice = (price) => {
    var priceToString = price.toString();
    var formattedPrice = priceToString.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    return 'RM' + formattedPrice;
}

const view = (tourId) => {
    try {
        router.get(route('package.show', { package: tourId } ))
    } catch (error) {
        console.error('Error in viewing tour:', error)
    }
}
</script>


<template>
    <NavBar />

    <header class="mt-2">
        <div id="heading-container" class="bg-white font-sans py-4">
            <div id="heading" class="m-auto lg:w-5/6 xl:w-10/12 2xl:w-7/12">
                <h1 class="text-4xl font-black">Compare Tours</h1>
            </div>
        </div>
    </header>
    
    <section class="m-auto lg:w-5/6 xl:w-10/12 2xl:w-7/12 overflow-x-auto">
        <div id="table-container" class="text-left text-lg">
            <table class="table-fixed border-separate border-spacing-x-3 border-spacing-y-2 w-full">
            <thead>
                <tr>
                    <th>Trip</th>
                    <th v-for="tour in tours">
                        <a>
                            <img class="rounded-xl mb-4 aspect-video object-cover" src="https://keycdn.borneoecotours.com/images/cache/tours/cover/bb07a-1000x1000.webp">
                            <h2 class="text-2xl">{{ tour.name }}</h2>
                        </a>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr class="h-10">
                    <th>Duration</th>
                    <td v-for="tour in tours">{{ tour.day }} Days
                    </td>
                </tr>
                <tr>
                    <th>Ratings & Reviews</th>
                    <td v-for="tour in tours">None</td>
                </tr>
                <tr>
                    <th>Age Range</th>
                    <td v-for="tour in tours">{{ tour.min_age }} - {{ tour.max_age }} years old</td>
                </tr>
                <tr>
                    <th>Destinations</th>
                    <td v-for="tour in tours">{{ tour.destination }}</td>
                </tr>
                <tr>
                    <th>Travel Intensity</th>
                    <td v-for="tour in tours">{{ tour.travel_intensity }}</td>
                </tr>
                <tr>
                    <th></th>
                    <td v-for="tour in tours" class="font-black text-2xl">{{ formatPrice(tour.base_price) }}</td>
                </tr>
                <tr>
                    <th></th>
                    <td v-for="tour in tours">
                        <SecondaryButton @click="view(tour.id)" class="w-full rounded-xl text-indigo-800 bg-indigo-100">
                            View Tour
                        </SecondaryButton>
                    </td>
                </tr>
            </tbody>
            </table>
        </div>
    </section>
</template>