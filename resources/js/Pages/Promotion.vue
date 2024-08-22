<script setup>
import MarginLayout from '@/Layouts/MarginLayout.vue';
import NavBar from '@/Components/NavBar.vue';
import Footer from '@/Components/Footer.vue';

import Button from 'primevue/button';
import Divider from 'primevue/divider';
import Tag from 'primevue/tag';

import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { useFormatPrice } from '@/Composables/formatPrice';

const props = defineProps({
    tours: {
        type: Object
    }
});

function calculateTourLowestPrice(tour) {
    const packages = ref(tour.packages);
    var price = packages.value[0].price;

    packages.value.forEach(item => {
        var itemPrice = parseInt(item.price);

        if (price > itemPrice) {
            price = itemPrice;
        }
    })

    return price;
}

function getTourLowestPrice(tour) {
    return useFormatPrice(calculateTourLowestPrice(tour), 0);
}

function getTourDiscount(tour) {
    return tour.discount.percentage + '% discount';
}

function getTourPriceDiscount(tour) {
    const discount = tour.discount.percentage;
    const lowestPrice = calculateTourLowestPrice(tour);
    const discountedPrice = lowestPrice - (lowestPrice * (discount / 100));

    return useFormatPrice(discountedPrice, 0);
}

const bookTour = (tour) => {
    router.get(route('tour.show', { tour: tour.slug }));
}

const dummyImage = 'https://www.datocms-assets.com/101439/1708607396-chureito-pagoda.jpg?auto=format&h=700&w=1000';

</script>

<template>
    <MarginLayout>
        <NavBar />
        <div class="mx-auto h-full lg:w-5/6 xl:max-w-7xl">
            <div class="my-8">

                <!-- Title Section -->
                <div class="mb-4">
                    <h1 class="text-2xl">Promotion</h1>
                </div>

                <!-- Tour Promotion Section -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div 
                        v-for="tour in tours" 
                        class="bg-white flex flex-col place-items-start items-stretch gap-4 rounded-3xl p-4 shadow-lg"
                    >
                        <div class="aspect-square rounded-lg bg-cyan-100">
                            <picture>
                                <img class="rounded-lg h-full object-cover" :src="dummyImage">
                            </picture>
                        </div>

                        <div>
                            <h2 class="line-clamp-1">{{ tour.name }}</h2>
                            <p class="line-clamp-3 text-justify">{{ tour.description }}</p>
                            <Divider />
                            <div class="flex justify-between items-center">
                                <div class="flex flex-col">
                                    <div class="flex gap-2 items-center">
                                        <span class="text-sm line-through">From {{ getTourLowestPrice(tour) }}</span>
                                        <Tag :value="getTourDiscount(tour)" severity="success" />
                                    </div>
                                    <span class="font-bold text-2xl">From {{ getTourPriceDiscount(tour) }}</span>
                                </div>
                                <Button 
                                    label="Book Now"
                                    text rounded outlined
                                    class="max-w-fit"
                                    @click="bookTour(tour)"
                                />
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <Footer />
    </MarginLayout>
</template>