<script setup>
import NavBar from '@/Components/NavBar.vue';
import TableTableHeader from '@/Pages/Compare/Partials/TourTableHeader.vue';
import TourTableRow from '@/Pages/Compare/Partials/TourTableRow.vue';

const props = defineProps({
    tours: {
        type: [Array, Object],
        required: true
    }
});
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
                    <TableTableHeader :tours="tours" />
                </thead>
                <tbody>
                    <TourTableRow :tours="tours" />
                </tbody>
            </table>
        </div>
    </section>
</template>