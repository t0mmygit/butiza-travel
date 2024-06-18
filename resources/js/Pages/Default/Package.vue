<script setup>
import Card from '@/Components/Card.vue'
import NavBar from '@/Components/NavBar.vue' 
const props = defineProps(['tours'])

</script>

<template>
    <!-- NavbAR -->
    <NavBar />


    <div class="w-screen">
        <div id="container" class="lg:w-4/5 m-auto">
            <div id="content" class="h-screen text-center"> 
                <div class="mt-4 my-6">
                    <h1 class="text-xl font-black pb-2">TITLE</h1>
                    <div id="content-wrapper">
                        <p class="w-[600px] mx-auto">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <Card
                        v-for="tour in tours"
                        :key="tour.id"
                        :tour="tour"
                    />
                </div>
            </div>
        </div>
    </div>
</template>