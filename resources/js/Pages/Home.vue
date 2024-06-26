<script setup>
import NavBar from '@/Components/NavBar.vue';
import Footer from '@/Components/Footer.vue';
import Button from 'primevue/button';
import IconField from 'primevue/iconfield';
import InputText from 'primevue/inputtext';
import InputIcon from 'primevue/inputicon';
import InputGroup from 'primevue/inputgroup';
import Carousel from 'primevue/carousel';
import HomeCarousel from '@/Pages/Home/Carousel.vue';
import Modal from '@/Components/Modal.vue';
import ModalGroupTour from '@/Components/Modal/ModalGroupTour.vue';
import { router, Head } from '@inertiajs/vue3';
import { ref } from 'vue';

const tourModal = ref(false);

function routeTo(routePath) {
    if (routePath === 'groupTour') tourModal.value = true;
    else router.get(route(routePath));
}

const homeImage = 'https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D';
const dummyImage = 'https://cdn.tourradar.com/s3/tour/1500x800/228465_624e71afe4389.jpg';

const heroBoxes = [
    {
        img: 'https://static.travelstride.com/store/ef/c8a39facc946ed960e425e6e27569d/4f8b67971e48794c9d63975391f5eb02.jpg',
        header: 'Explore Our Planned Tours',
        paragraph: ' Duis aute irure dolor in reprehenderit in voluptate.',
        button: 'Explore',
        route: 'explore.index'
    },
    {
        img: 'https://static.travelstride.com/store/ef/c8a39facc946ed960e425e6e27569d/4f8b67971e48794c9d63975391f5eb02.jpg',
        header: 'Customize Your Adventure',
        paragraph: 'Duis aute irure dolor in reprehenderit in voluptate.',
        button: 'Customize',
        route: 'customize'
    },
    {
        img: 'https://static.travelstride.com/store/ef/c8a39facc946ed960e425e6e27569d/4f8b67971e48794c9d63975391f5eb02.jpg',
        header: 'Share the Adventure, Split the Cost',
        paragraph: ' Duis aute irure dolor in reprehenderit in voluptate.',
        button: 'Group Tour',
        route: 'groupTour'
    },
    {
        img: 'https://static.travelstride.com/store/ef/c8a39facc946ed960e425e6e27569d/4f8b67971e48794c9d63975391f5eb02.jpg',
        header: 'Explore the Community',
        paragraph: ' Duis aute irure dolor in reprehenderit in voluptate.',
        button: 'Community',
        route: 'community'
    },
];

const images = ref([
    {
        name: 'name',
        price: 'name',
    }
]);

</script>

<template>
    <Head title="Home" />

    <Modal
        :show="tourModal"
        @close="tourModal = false"
    >
        <ModalGroupTour />  
    </Modal>

       <!-- <div id="content-container" class="py-10 mb-8">
                    <h1 class="text-2xl text-center">Start Your Adventure Around Malaysia</h1>
                    <InputGroup class="py-8">
                        <IconField iconPosition="left" class="flex mx-auto">
                            <InputIcon class="pi pi-search" />
                            <InputText placeholder="Search" class="rounded-full" />
                        </IconField>
                    </InputGroup>
                </div> -->
                

    <div class="flex flex-col min-h-screen">
        <NavBar />
        <main class="flex-1 bg-gray-200 h-full w-full">
            <!-- <div class="bg-cyan-400 lg:max-h-[600px] bg-fixed">
                <img :src="homeImage" class="object-cover max-h-[600px] w-full">
            </div> -->
            <HomeCarousel />

            <section class="my-8 mx-auto max-w-2xl xl:max-w-7xl">
                <h1 class="mb-4">Start Your Journey</h1>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 min-h-min">
                    <div v-for="heroBox in heroBoxes" class="bg-white min-h-40 rounded-lg p-3 cursor-pointer shadow-lg">
                        <!-- <img 
                            :src="heroBox.img"
                            class="rounded-md"
                        > -->
                        <div class="mb-4">
                            <h3 class="font-bold">{{ heroBox.header }}</h3>
                            <p>{{ heroBox.paragraph }}</p>
                        </div>
                        <Button 
                            :label="heroBox.button" 
                            severity="contrast" outline  
                            class="w-full"
                            @click="routeTo(heroBox.route)"
                        />
                    </div>
                </div>
            </section>
        </main>
        <Footer />
    </div>
</template>