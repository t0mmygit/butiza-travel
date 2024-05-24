<script setup>
import MarginLayout from '@/Layouts/MarginLayout.vue';
import ChirpBox from '@/Components/ChirpBox.vue';
import TextInput from '@/Components/TextInput.vue';
import RoundedButton from '@/Components/RoundedButton.vue';
import IconButton from 'primevue/button';

const props = defineProps({
    group_tours: {
        type: [Array, Object]
    }
});

console.log(props.group_tours);

</script>

<template>
    <MarginLayout>
        <div class="bg-white h-fit shadow py-6">
            <div class="max-w-sm lg:max-w-full lg:mx-16 flex items-center relative">
                <SvgLogo />
            </div>

        </div>
        <section class="mx-auto my-8 lg:flex xl:flex max-w-2xl xl:max-w-7xl">
            <div class="bg-neutral-300 w-60"></div>
            <div class="lg:max-w-2xl">
                <ChirpBox>Home</ChirpBox>
                <ChirpBox>
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
                </ChirpBox>
                <div class="p-4">
                    <ChirpBox v-for="group_tour in group_tours" class="rounded-md mb-4">
                        <div class="flex items-center mb-2">
                            <div class="bg-neutral-500 rounded size-10 mr-3"></div>
                            <h3 class="mr-3">Username</h3>
                            <!-- <small>timestamp</small> -->
                        </div>
                        <h2>{{ group_tour.tour.name }}</h2>
                        <p class="mb-2">{{ group_tour.description }}</p>
                        <div class="flex justify-between">
                            <div class="flex gap-2 items-center">
                                <IconButton icon="pi pi-heart" severity="secondary" text rounded aria-label="Like" />
                                <IconButton icon="pi pi-bookmark" severity="secondary" text rounded aria-label="Bookmark" />
                                <IconButton icon="pi pi-share-alt" severity="secondary" text rounded aria-label="Share" />
                            </div>
                            <div class="flex gap-2">
                                <RoundedButton>View Group</RoundedButton>
                                <RoundedButton>Join Group</RoundedButton>
                            </div>
                        </div>
                    </ChirpBox>
                </div>
            </div>
            <div class="bg-neutral-300 w-96"></div>
        </section>
    </MarginLayout>
</template>