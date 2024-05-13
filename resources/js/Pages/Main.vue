<script setup>
import NavBar from '@/Components/NavBar.vue';
import Footer from '@/Components/Footer.vue';
import Button from 'primevue/button';
import { router } from '@inertiajs/vue3';

const customize = () => {
    router.get(route('customize'));
}
</script>

<template>
    <div class="flex flex-col min-h-screen">
        <NavBar />
        <main class="flex-1">
            <div id="main-container">
                <div id="content-container" class="relative py-10">
                    <!-- <img src="https://www.instagram.com/p/C1BOVe_pmQv/" class="absolute"> -->
                    <h1 class="font-black text-2xl text-center">Start Your Adventure Around Malaysia</h1>
                    <div class="flex justify-center gap-16 py-10 text-center">
                        <div id="group-tour" class="relative">
                            <div class="bg-white h-fit w-96 rounded-lg shadow-md p-6 items-center">
                                <div class="mb-4">
                                    <h3 class="font-bold">Customize Your Adventure</h3>
                                    <p>Create your dream vacation with our easy planner. Craft your perfect adventure.</p>
                                </div>
                                <Button @click="customize" label="Customize" text raised class="rounded-full text-sm" />
                            </div>
                        </div>
                        <div id="custom-tour">
                            <div class="flex flex-col items-center bg-white min-h-full w-96 rounded-lg shadow-md p-6">
                                <div class="mb-4">
                                    <h3 class="font-bold">Share the Adventure, Split the Cost</h3>
                                    <p>Traveling with friends made easy! Split bills hassle-free on group tours.</p>
                                </div>
                                <Button label="Group Tour" text raised class="rounded-full text-sm" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <Footer />
    </div>
</template>