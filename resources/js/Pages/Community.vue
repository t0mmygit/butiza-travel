<script setup>
import MarginLayout from '@/Layouts/MarginLayout.vue';
import ChirpBox from '@/Components/ChirpBox.vue';
import TextInput from '@/Components/TextInput.vue';
import RoundedButton from '@/Components/RoundedButton.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import Button from 'primevue/button';

const props = defineProps({
    group_tours: {
        type: [Array, Object]
    }
});

const navButton = [
    {
        label: "Home",
        icon: "pi pi-home"
    },
    {
        label: "Bookmark",
        icon: "pi pi-bookmark"
    }
]

</script>

<template>
    <MarginLayout>

        <div class="bg-white h-fit shadow py-6">
            <div class="max-w-sm lg:max-w-full lg:mx-16 flex items-center relative">
                <SvgLogo />
            </div>
        </div>

        <!-- <section class="mx-auto my-8 lg:flex xl:flex max-w-2xl xl:max-w-7xl"> -->
        <div class="flex lg:w-11/12 mx-auto">
            <section class="flex flex-row w-full min-h-dvh justify-center">
                <header class="bg-neutral-300 w-fit ml-auto">
                    <div>
                        <nav class="flex flex-col items-start">
                            <Button v-for="nav in navButton" :label="nav.label" :icon="nav.icon" plain text />
                        </nav>
                    </div>
                </header>
    
                <main class="flex items-start flex-grow min-h-full w-full border border-primary border-y-0 p-4">
                    <div class="w-full">
                        <div class="grid grid-cols-2 w-full justify-between gap-4">
                            <ChirpBox v-for="group_tour in group_tours" class="rounded-md mb-4">
                                <div class="flex items-center mb-2 justify-between">
                                    <div class="flex items-center">
                                        <div class="bg-neutral-500 rounded size-10 mr-3"></div>
                                        <h3 class="mr-3">{{ group_tour.user.name }}</h3>
                                    </div>
                                    <Dropdown v-if="group_tour.user.id === $page.props.auth.user.id" class="mr-2">
                                        <template #trigger>
                                            <button>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                                </svg>
                                            </button>
                                        </template>
                                        <template #content>
                                            <button class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:bg-gray-100 transition duration-150 ease-in-out">
                                                Edit
                                            </button>
                                            <DropdownLink href="#" as="button">
                                                Delete
                                            </DropdownLink>
                                        </template>
                                    </Dropdown>
                                </div>
                                <h2>{{ group_tour.tour.name }}</h2>
                                <p class="mb-2">{{ group_tour.description }}</p>
                                <div class="flex justify-between">
                                    <div class="flex gap-2 items-center">
                                        <Button icon="pi pi-heart" severity="secondary" text rounded aria-label="Like" />
                                        <Button icon="pi pi-bookmark" severity="secondary" text rounded aria-label="Bookmark" />
                                        <Button icon="pi pi-share-alt" severity="secondary" text rounded aria-label="Share" />
                                    </div>
                                    <div class="flex gap-2">
                                        <RoundedButton>View Group Tour</RoundedButton>
                                    </div>
                                </div>
                            </ChirpBox>
                        </div>
                        <!-- <div class="bg-neutral-300"></div> -->
                    </div>
    
                    <!-- <ChirpBox>
                        <div class="flex">
                            <div>
                                <div class="bg-neutral-500 rounded size-10 mr-3"></div>
                            </div>
                            <div class="flex flex-col w-full">
                                <TextInput placeholder="What's happening" />
                                <div class="h-10 flex flex-1 items-center justify-between">
                                    <div class="flex gap-2 items-center">
                                        <div class="bg-neutral-500 size-6 rounded"></div>
                                        <div class="bg-neutral-500 size-6 rounded"></div>
                                        <div class="bg-neutral-500 size-6 rounded"></div>
                                        <div class="bg-neutral-500 size-6 rounded"></div>
                                    </div>
                                    <RoundedButton>CHIRP</RoundedButton>
                                </div>
                            </div>
                        </div>
                    </ChirpBox> -->
                </main>
            </section>
        </div>
    </MarginLayout>
</template>