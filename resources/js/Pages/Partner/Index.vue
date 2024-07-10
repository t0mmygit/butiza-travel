<script setup>
import NavLink from '@/Components/NavLink.vue';
import PartnerRequestForm from '@/Pages/Partner/PartnerRequestForm.vue';

import Divider from 'primevue/divider';

</script>

<template>
    <div class="bg-white h-20 shadow z-50 sticky top-0">
        <div class="flex items-center justify-between mx-auto h-full lg:w-5/6 xl:max-w-7xl">
            <ApplicationLogo />

            <div class="flex items-center gap-6">
                <NavLink label="About Butiza Travel" :href="route('about')" :active="route().current('about')" />
                <NavLink label="Partner Programme" :href="route('partner.create')" :active="route().current('partner.create')" />
            </div>
        </div>
    </div>

    <div class="mx-auto w-full lg:w-5/6 xl:max-w-7xl">
        <section class="bg-white p-6 rounded-lg">
            <PartnerRequestForm />

            <Divider />
        </section>
    </div>
</template>