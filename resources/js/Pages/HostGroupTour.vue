s<script setup>
import MarginLayout from '@/Layouts/MarginLayout.vue';
import Avatar from 'primevue/avatar';
import Button from 'primevue/button';
import Calendar from 'primevue/calendar';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import { router, useForm } from '@inertiajs/vue3';  
import { computed } from 'vue';

const props = defineProps({
    tour: {
        type: [Array, Object]
    }
});

const tourId = computed(() => {
    return props.tour.length ? props.tour[0].id : null;
});

const preset = () => {
    try {
        router.get(route('explore.index', { mode: true }));
    } catch (error) {
        console.error(error);
    }
};

const form = useForm({
    tour_id: tourId,
    date: null,
    max_passenger: null,
    description: null,
});

</script>

<template>
    <MarginLayout>
        <div class="bg-white h-fit shadow py-6">
            <div class="max-w-sm lg:max-w-full lg:mx-16 flex items-center relative">
                <SvgLogo />
            </div>
        </div>

        <section class="mx-auto my-8 lg:flex xl:flex max-w-2xl xl:max-w-7xl">
            <Avatar icon="pi pi-user" class="mt-3 mr-4 bg-white" size="large" shape="circle" />
            <div class="grow">
                <div class="
                max-w-sm
                lg:max-w-lg
                xl:max-w-4xl
                bg-white shadow rounded py-6 px-5 relative"
                >
                    <!-- Title -->
                    <div v-if="!tour.length">
                        <div class="flex items-center">
                            <div class="bg-white rotate-45 size-4 absolute xl:left-[-6px]"></div>
                            <h1>Let's get started by creating your group tour.</h1>
                        </div>
                        <p class="text-lg mb-8">
                            Would you like to customize or select a preset tour?
                        </p>
                        <div class="flex gap-4">
                            <Button @click="preset">Select a preset tour</Button>
                            <Button>Customize a group tour</Button>
                        </div>
                    </div>
                    <div v-else>
                        <div class="flex items-center mb-6">
                            <div class="bg-white rotate-45 size-4 absolute xl:left-[-6px]"></div>
                            <h1>Next, please fill in these forms to create your group tour.</h1>
                        </div>
                        <form @submit.prevent="form.post(route('host.create'))">
                            <div class="mb-8 flex flex-col gap-2">
                                <h4>When should this group tour to start? *</h4>
                                <Calendar v-model="form.date" dateFormat="dd MM yy" />
                                <InputError :message="form.errors.date" />
                                <InputError :message="form.errors.tours_id" />
                            </div>
                            <div class="mb-8 flex flex-col gap-2">
                                <h4>What is the maximum people for this tour? *</h4>
                                <TextInput v-model="form.max_passenger" placeholder="How many?" type="number" :error="form.errors.max_passenger" />
                            </div>
                            <div class="mb-8 flex flex-col gap-2">
                                <h2>Description</h2>
                                <textarea 
                                    v-model="form.description"
                                    placeholder="Provide a brief overview of the group tour."
                                    class="rounded-md border-neutral-300 shadow"
                                ></textarea>
                                <InputError :message="form.errors.description" />
                            </div>
                            <div class="flex place-content-end">
                                <Button
                                    type="submit"
                                    label="Create Group Tour"
                                />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div v-if="tour.length" class="bg-white rounded-md shadow-md min-w-72 max-w-72 h-fit hidden xl:block">
                <div>
                    <img 
                        src="https://static.travelstride.com/store/51/ad4f08f81843578f3ea235b037593e/b669206bb8425f9409b22d1a87c8d181.jpg"
                        class="rounded-t-md"
                    >
                </div>
                <div class="p-3">
                    <h2>{{ tour[0].name }}</h2>
                    <div class="flex items-center justify-between">
                        <span class="text-xl font-medium">{{ tour[0].day }} Days</span>
                        <span class="text-xl font-medium"><small class="text-black">From</small> RM{{ tour[0].base_price }}</span>
                    </div>
                </div>
            </div>
        </section>
    </MarginLayout>
</template>