<script setup>
import NavBar from '@/Components/NavBar.vue';
import Inbox from '@/Pages/Partner/Account/Inbox.vue';
import UserSettings from '@/Pages/Partner/Account/UserSettings.vue';

import Menu from 'primevue/menu';

import { ref } from 'vue';

const props = defineProps({
    partner: {
        type: [Array, Object],
        required: true,
    },
});

const sectionIndex = ref(0);
const notifications = ref(props.partner.notifications);

console.log(sectionIndex.value)

const items = ref([
    {
        label: 'Inbox', 
        icon: 'pi pi-inbox', 
        command: () => { sectionIndex.value = 0; },
        class: sectionIndex.value === 0 ? 'bg-neutral' : '',
    },
    {
        label: 'Account', 
        icon: 'pi pi-user', 
        command: () => { sectionIndex.value = 1; },
        class: sectionIndex.value === 1 ? 'bg-neutral' : '',
    },
]);

</script>

<template>
    <NavBar partner />

    <div class="mx-auto w-full lg:w-5/6 xl:max-w-7xl">
        <div class="my-8">
            <!-- <h1>Partner Account</h1> -->
            <div class="flex gap-4 min-h-min">
                <section>
                    <Menu :model="items" />
                </section>
                <section class="flex-grow">
                    <div class="w-full">
                        <Inbox v-if="sectionIndex === 0" :notifications="notifications" />
                        <UserSettings v-if="sectionIndex === 1" :partner="partner" />
                    </div>
                </section>
            </div>
        </div>
    </div>
</template>