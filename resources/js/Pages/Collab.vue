<script setup>
import MarginLayout from '@/Layouts/MarginLayout.vue';
import Button from 'primevue/button';
import NavLink from '@/Components/NavLink.vue';

import TextInput from '@/Components/TextInput.vue';
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
        <div class="mx-auto  w-full lg:w-5/6 xl:max-w-7xl">
            <!-- <h1 class="text-center">Collaboration</h1> -->
            <div class="bg-white p-6 rounded-lg">
                <form>
                    <div class="p-6">
                        <h1 class="text-center text-xl text-primary">Partner With Us!</h1>
                    </div>
                    <div class="flex gap-6">
                        <TextInput label="First Name" required />
                        <TextInput label="Last Name" required />
                    </div>
                    <div class="flex gap-6">
                        <TextInput label="Email Adress" required />
                        <TextInput label="Phone Number" required />
                    </div>
                </form>
            </div>
        </div>
    </MarginLayout>
</template>