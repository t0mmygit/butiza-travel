<script setup>
import Button from 'primevue/button';
</script>

<template>
    <div class="flex flex-col justify-center items-center p-6 mb-6">
        <div class="flex lg:justify-center lg:col-start-2 mb-4">
            <ApplicationLogo />
        </div>
        <h2 class="font-bold text-2xl mb-2">Create or join a group tour</h2>
        <p class="mb-6">Discover group tours worldwide.</p>
        <div class="flex flex-col gap-4">
            <Button 
                label="Host a group tour" 
                icon="pi pi-user-plus"
                plain text raised 
                class="shadow-none text-base outline outline-1 outline-gray-300 rounded hover:outline-primary hover:bg-white"
                @click="$inertia.get(route('group-tour.index'))"
            />
            <Button
                label="Join a group tour"
                icon="pi pi-search"
                plain text raised
                class="shadow-none text-base outline outline-1 outline-gray-300 rounded hover:outline-primary hover:bg-white"
                @click="$inertia.get(route('community'))"
            />
        </div>
        <small class="text-gray-500 text-center mt-12">
            <a href="#" class="underline">Why host a group tour?</a>
        </small>
    </div>
</template>