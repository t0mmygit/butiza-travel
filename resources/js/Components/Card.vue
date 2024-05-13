<script setup>
import { router } from '@inertiajs/vue3';

const props = defineProps(['tour'])

const navigate = (tourID) => {
    console.log("Navigating... ID:", tourID)
    router.get(route('package.show', { id: tourID }))
}

</script>

<template>
    <div v-on:click="navigate(tour.id)" class="lg:w-2/6 w-1/2 max-h-[500px] rounded-md shadow-md hover:shadow-xl ease-in-out transition duration-300 cursor-pointer">
        <div id="image" class="bg-cyan-200 overflow-hidden aspect-square relative rounded-md">
            <img src="https://keycdn.borneoecotours.com/images/cache/tours/cover/bb07a-1000x1000.webp"
        </div>
        <div id="container" class="mx-4 my-3 h-max">
            <div id="content" class="text-left gap-4">
                <h1 class="uppercase text-lg font-black">{{ tour.name }}</h1>
                <p class="truncate leading-tight">{{ tour.description }}</p>
            </div>
            <div id="bottom" class="grid grid-cols-2">
                <div class="flex justify-start font-black">{{ tour.day  }}D {{ tour.night }}N</div>
                <div class="flex justify-end font-black">MYR {{ tour.base_price }}</div>
            </div>
        </div>
    </div>
</template>