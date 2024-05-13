<script setup>
import { defineProps } from 'vue';

import NavBar from '@/Components/NavBar.vue'

const props = defineProps(['tour'])
console.log(props.tour)
</script>

<template>
    <!-- Navbar -->
    <NavBar />

    <div id="container" class="lg:w-4/5 m-auto h-screen py-8">
        <h1 class="text-2xl uppercase font-black">{{ tour[0].name }}</h1>
        <div id="details" class="bg-gray-300 mt-4 h-60 rounded-md p-6 mb-6">
            <div class="mb-4">
                <h3 class="text-xl font-bold mb-4">Overview</h3>
                <p class="text-neutral-600">{{ tour[0].description }}</p>
            </div>
            <div class="grid grid-cols-4 gap-4">
                <div class="bg-white flex h-16 rounded items-center py-4 px-6">
                    <div id="icon" class="items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                            <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25ZM12.75 6a.75.75 0 0 0-1.5 0v6c0 .414.336.75.75.75h4.5a.75.75 0 0 0 0-1.5h-3.75V6Z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div id="info" class="ml-4 text-left ">
                        <h3 class="font-black">Duration</h3>
                        <span>{{ tour[0].day }} Days {{ tour[0].night }} Nights</span>
                    </div>
                </div>

                <div class="bg-white flex h-16 rounded items-center py-4 px-6">
                    <div id="icon" class="items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                            <path fill-rule="evenodd" d="M8.25 6.75a3.75 3.75 0 1 1 7.5 0 3.75 3.75 0 0 1-7.5 0ZM15.75 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM2.25 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM6.31 15.117A6.745 6.745 0 0 1 12 12a6.745 6.745 0 0 1 6.709 7.498.75.75 0 0 1-.372.568A12.696 12.696 0 0 1 12 21.75c-2.305 0-4.47-.612-6.337-1.684a.75.75 0 0 1-.372-.568 6.787 6.787 0 0 1 1.019-4.38Z" clip-rule="evenodd" />
                            <path d="M5.082 14.254a8.287 8.287 0 0 0-1.308 5.135 9.687 9.687 0 0 1-1.764-.44l-.115-.04a.563.563 0 0 1-.373-.487l-.01-.121a3.75 3.75 0 0 1 3.57-4.047ZM20.226 19.389a8.287 8.287 0 0 0-1.308-5.135 3.75 3.75 0 0 1 3.57 4.047l-.01.121a.563.563 0 0 1-.373.486l-.115.04c-.567.2-1.156.349-1.764.441Z" />
                        </svg>
                    </div>
                    <div id="info" class="ml-4 text-left ">
                        <h3 class="font-black">Group Size</h3>
                        <span>Maximum of {{ tour[0].max_passenger }} people</span>
                    </div>
                </div>

                <div class="bg-white flex h-16 rounded items-center py-4 px-6">
                    <div id="icon" class="items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                            <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div id="info" class="ml-4 text-left ">
                        <h3 class="font-black">Age Range</h3>
                        <span>{{ tour[0].min_age }} - {{ tour[0].max_age }}</span>
                    </div>
                </div>

                <div class="bg-white flex h-16 rounded items-center py-4 px-6">
                    <div id="icon" class="items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                            <path fill-rule="evenodd" d="M12 2.25a.75.75 0 0 1 .75.75v.756a49.106 49.106 0 0 1 9.152 1 .75.75 0 0 1-.152 1.485h-1.918l2.474 10.124a.75.75 0 0 1-.375.84A6.723 6.723 0 0 1 18.75 18a6.723 6.723 0 0 1-3.181-.795.75.75 0 0 1-.375-.84l2.474-10.124H12.75v13.28c1.293.076 2.534.343 3.697.776a.75.75 0 0 1-.262 1.453h-8.37a.75.75 0 0 1-.262-1.453c1.162-.433 2.404-.7 3.697-.775V6.24H6.332l2.474 10.124a.75.75 0 0 1-.375.84A6.723 6.723 0 0 1 5.25 18a6.723 6.723 0 0 1-3.181-.795.75.75 0 0 1-.375-.84L4.168 6.241H2.25a.75.75 0 0 1-.152-1.485 49.105 49.105 0 0 1 9.152-1V3a.75.75 0 0 1 .75-.75Zm4.878 13.543 1.872-7.662 1.872 7.662h-3.744Zm-9.756 0L5.25 8.131l-1.872 7.662h3.744Z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div id="info" class="ml-4 text-left ">
                        <h3 class="font-black">Travel Intensity</h3>
                        <span>{{ tour[0].travel_intensity }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div id="itinerary" class="flex">
            <h1 class="text-2xl uppercase font-black">Itinerary</h1>
            
        </div>
    </div>
</template>