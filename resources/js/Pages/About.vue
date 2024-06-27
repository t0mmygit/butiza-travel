<script setup>
import MarginLayout from '@/Layouts/MarginLayout.vue';
import Button from 'primevue/button';
import NavLink from '@/Components/NavLink.vue';
</script>

<template>
    <div class="bg-white h-20 shadow z-50 sticky top-0">
        <div class="flex items-center justify-between mx-auto h-full lg:w-5/6 xl:max-w-7xl">
            <SvgLogo />

            <div class="flex items-center gap-6">
                <NavLink label="About Butiza Travel" :href="route('about')" :active="route().current('about')" />
                <NavLink label="Collaboration" :href="route('collab')" :active="route().current('collab')" />
            </div>
            <!-- Content -->
        </div>
    </div>

    <MarginLayout>
        <div class="mx-auto h-full w-full lg:w-5/6 xl:max-w-7xl">
            <h1>About Us</h1>
        </div>
    </MarginLayout>
</template>