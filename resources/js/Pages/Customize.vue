<script setup>
import MarginLayout from '@/Layouts/MarginLayout.vue';

// PrimeVue 
import Steps from 'primevue/steps';
import Avatar from 'primevue/avatar';

import { ref } from 'vue';

const active = ref(0);

const items = ref([
    {
        label: 'Start',
        icon: 'pi pi-user'
    },
    {
        label: 'Where'
    },
    {
        label: 'How'
    },
    {
        label: 'What'
    },
    {
        label: 'Who'
    },
    {
        label: 'When'
    },
    {
        label: 'Your Trip',
        icon: 'pi pi-check'
    }
]);
</script>

<template>
    <MarginLayout>
        <div id="stepper" class="bg-white h-fit shadow py-2">
            <div class="m-auto max-w-sm lg:max-w-4xl">
                <Steps v-model:activeStep="active" :model="items" class="text-xs" />
            </div>
        </div>
        <section class="my-8 mx-auto lg:max-w-3xl relative">
            <div class="flex">
                <div class="flex">
                    <Avatar icon="pi pi-user" class="mt-3 mr-4 bg-white" size="large" shape="circle" />
                    <!-- <div class="bg-white rotate-45 size-4"></div> -->
                </div>
                <div class="bg-white shadow rounded py-6 px-5 relative z-1">
                    <div class="m-auto
                        max-w-sm
                        lg:max-w-4xl"
                    >
                    <div class="bg-red-500 rotate-45 size-4 top-0 shadow absolute left-[-6px] z-50"></div>
                        <h2>Hello! Let's brief you on how this all work.</h2>
                        <div>
                            <h3></h3>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </MarginLayout>
</template>