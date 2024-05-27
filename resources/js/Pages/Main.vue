<script setup>
import NavBar from '@/Components/NavBar.vue';
import Footer from '@/Components/Footer.vue';
import Button from 'primevue/button';
import IconField from 'primevue/iconfield';
import InputText from 'primevue/inputtext';
import InputIcon from 'primevue/inputicon';
import InputGroup from 'primevue/inputgroup';
import Modal from '@/Components/Modal.vue';
import ModalGroupTour from '@/Components/Modal/ModalGroupTour.vue';
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    isAuthenticated: {
        type: Boolean,
    }
});

const tourModal = ref(false);

const customize = () => {
    router.get(route('customize'));
};

</script>

<template>
    <Modal
        :show="tourModal"
        @close="tourModal = false"
    >
        <ModalGroupTour />
    </Modal>

    <div class="flex flex-col min-h-screen">
        <NavBar :isAuthenticated="isAuthenticated" />
        <main class="flex-1">
            <div id="main-container" class="bg-local bg-cover bg-center" style="background-image: url('https://keycdn.borneoecotours.com/images/cache/tours/cover/bb07a-1000x1000.webp');">
                <div id="content-container" class="py-10">
                    <h1 class="text-2xl text-center">Start Your Adventure Around Malaysia</h1>
                    <InputGroup class="py-8">
                        <IconField iconPosition="left" class="flex mx-auto">
                            <InputIcon class="pi pi-search" />
                            <InputText placeholder="Search" class="rounded-full" />
                        </IconField>
                    </InputGroup>
                    <div class="flex justify-center gap-16 py-10 text-center">
                        <div id="group-tour" class="relative">
                            <div class="bg-white h-fit w-96 rounded-lg shadow-md p-6 items-center">
                                <div class="mb-4">
                                    <h3 class="font-bold">Customize Your Adventure</h3>
                                    <p>Create your dream vacation with our easy planner. Craft your perfect adventure.</p>
                                </div>
                                <Button @click="customize" label="Customize" text raised class="rounded-full bg-white text-sm" />
                            </div>
                        </div>
                        <div id="custom-tour">
                            <div class="flex flex-col items-center bg-white min-h-full w-96 rounded-lg shadow-md p-6">
                                <div class="mb-4">
                                    <h3 class="font-bold">Share the Adventure, Split the Cost</h3>
                                    <p>Traveling with friends made easy! Split bills hassle-free on group tours.</p>
                                </div>
                                <Button @click="tourModal = true" label="Group Tour" text raised class="rounded-full bg-white text-sm" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <Footer />
    </div>
</template>