<script setup>
import MarginLayout from '@/Layouts/MarginLayout.vue'; 
import NavBar from '@/Components/NavBar.vue';
import Footer from '@/Components/Footer.vue';
import Button from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue'

import Breadcrumb from 'primevue/breadcrumb';
import Avatar from 'primevue/avatar';

import TourContent from '@/Components/Tour/TourContent.vue';
import TourStepper from '@/Components/Tour/TourStepper.vue';
import TourIconBox from '@/Components/Tour/TourIconBox.vue';
import TourTextBox from '@/Components/Tour/TourTextBox.vue';

import { router } from '@inertiajs/vue3';
import { defineProps } from 'vue';

const props = defineProps({
    tour: {
        type: Object,
        required: true
    },
    itinerary: {
        type: Object,
        required: true
    },
    day: {
        type: Object,
        required: true
    }
});

const reserveTour = () => {
    try {
        router.get(route('tour.reserve', { id: props.tour.id }))
    } catch (error) {
        console.error('Error reserving tour:', error);
    }
};
</script>

<template>
    <!-- Navbar -->
    <MarginLayout>
        <NavBar />
        <!-- <Breadcrumb  /> -->
        <div id="container" class="m-auto lg:w-5/6 xl:w-11/12 2xl:w-8/12 py-8">
            <div class="flex gap-4 mb-8">
                <div class="bg-gray-500 aspect-video w-full rounded-xl shadow">
                    <img 
                        src="https://static.travelstride.com/store/map_image/5061423/attachment/2a1bc8851483009a6ce5dce769eb39dd.jpg"
                        class="rounded-xl"
                    >
                </div>
                <div class="bg-white flex flex-col shadow w-full rounded-xl px-4 py-6">         
                    <h1 class="text-2xl uppercase font-black pb-4">{{ tour.name }}</h1>
                    <p class="text-neutral-600">{{ tour.description }}</p>
                    <div class="flex flex-col gap-2 mt-4">
                        <Button @click="reserveTour">Reserve</Button>
                        <SecondaryButton :icon="false" @click="customizeTour">Customize Tour</SecondaryButton>
                    </div>
                </div>
            </div>
            <div class="bg-white m-auto h-12 rounded-full shadow w-fit flex items-center justify-around gap-16 px-8 font-bold">
                <a href="#detail">Details</a>
                <a href="#itinerary">Itinerary</a>
                <a href="#available">Availability & Price</a>
                <a href="#note">Notes</a>
                <a href="#review">Reviews & Ratings</a>
            </div>
            <TourContent id="detail" title="Details">
                <div class="flex gap-4 mb-8">
                    <TourIconBox>
                        <template #header>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                                <path fill-rule="evenodd" d="M8.25 6.75a3.75 3.75 0 1 1 7.5 0 3.75 3.75 0 0 1-7.5 0ZM15.75 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM2.25 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM6.31 15.117A6.745 6.745 0 0 1 12 12a6.745 6.745 0 0 1 6.709 7.498.75.75 0 0 1-.372.568A12.696 12.696 0 0 1 12 21.75c-2.305 0-4.47-.612-6.337-1.684a.75.75 0 0 1-.372-.568 6.787 6.787 0 0 1 1.019-4.38Z" clip-rule="evenodd" />
                                <path d="M5.082 14.254a8.287 8.287 0 0 0-1.308 5.135 9.687 9.687 0 0 1-1.764-.44l-.115-.04a.563.563 0 0 1-.373-.487l-.01-.121a3.75 3.75 0 0 1 3.57-4.047ZM20.226 19.389a8.287 8.287 0 0 0-1.308-5.135 3.75 3.75 0 0 1 3.57 4.047l-.01.121a.563.563 0 0 1-.373.486l-.115.04c-.567.2-1.156.349-1.764.441Z" />
                            </svg>
                        </template>
                        <template #main>
                            <h3 class="font-black">Group Size</h3>
                            <span>Maximum of {{ tour.max_passenger }} people</span>
                        </template>
                    </TourIconBox>
                    <TourIconBox>
                        <template #header>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                                <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z" clip-rule="evenodd" />
                            </svg>
                        </template>
                        <template #main>
                            <h3 class="font-black">Age Range</h3>
                            <span>{{ tour.min_age }} - {{ tour.max_age }}</span>
                        </template>
                    </TourIconBox>
                    <TourIconBox>
                        <template #header>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                                <path fill-rule="evenodd" d="M12 2.25a.75.75 0 0 1 .75.75v.756a49.106 49.106 0 0 1 9.152 1 .75.75 0 0 1-.152 1.485h-1.918l2.474 10.124a.75.75 0 0 1-.375.84A6.723 6.723 0 0 1 18.75 18a6.723 6.723 0 0 1-3.181-.795.75.75 0 0 1-.375-.84l2.474-10.124H12.75v13.28c1.293.076 2.534.343 3.697.776a.75.75 0 0 1-.262 1.453h-8.37a.75.75 0 0 1-.262-1.453c1.162-.433 2.404-.7 3.697-.775V6.24H6.332l2.474 10.124a.75.75 0 0 1-.375.84A6.723 6.723 0 0 1 5.25 18a6.723 6.723 0 0 1-3.181-.795.75.75 0 0 1-.375-.84L4.168 6.241H2.25a.75.75 0 0 1-.152-1.485 49.105 49.105 0 0 1 9.152-1V3a.75.75 0 0 1 .75-.75Zm4.878 13.543 1.872-7.662 1.872 7.662h-3.744Zm-9.756 0L5.25 8.131l-1.872 7.662h3.744Z" clip-rule="evenodd" />
                            </svg>
                        </template>
                        <template #main>
                            <h3 class="font-black">Travel Intensity</h3>
                            <span>{{ tour.travel_intensity }}</span>
                        </template>
                    </TourIconBox>
                </div>
                <TourTextBox label="Destinations" :values="tour.destinations" />
                <TourTextBox label="Activities" :values="tour.destinations" />
                <div class="mt-8">
                    <h2>Highlights</h2>
                    <ul class="list-inside list-disc">
                        <li></li>
                    </ul>
                </div>
            </TourContent>
            <TourContent id="itinerary" title="Itinerary">
                <div class="bg-neutral h-40 mb-4">
                </div>
                <TourStepper 
                    :days="day"
                />
            </TourContent>
            <TourContent id="available" title="Dates & Availability">
            </TourContent>
            <TourContent id="notes" title="Notes">  
            </TourContent>
            <TourContent id="review" title="Reviews & Ratings">  
                <div class="mb-6">
                    <div class="flex items-center">
                        <Avatar icon="pi pi-user" class="mr-4" size="large" shape="circle" />
                        <h4>Username</h4>
                    </div>
                    <div class="ml-16">
                        <h3>Tour Title</h3>
                        <p>Description</p>
                    </div>
                </div>
            </TourContent>
        </div>
        <Footer />
    </MarginLayout>
</template>