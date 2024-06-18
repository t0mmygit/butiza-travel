s<script setup>
import MarginLayout from '@/Layouts/MarginLayout.vue';
import Avatar from 'primevue/avatar';
import Calendar from 'primevue/calendar';
import Button from 'primevue/button';

import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';

import { ref, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';

const selectedTour = ref(null);
const selectedContactMethod = ref(null);

const props = defineProps({
    tour: {
        type: Object,
        required: true
    },
    contact_methods: {
        type: Object,
        required: true
    }
});

const contactIcons = [
    { call: 'pi pi-phone' },
    { email: 'pi pi-envelope' },
    { whatsapp: 'pi pi-whatsapp' }
];

const getContactMethodIcon = (iconName) => {
    const icon = contactIcons.find(icon => iconName.toLowerCase() in icon );
    return icon ? icon[iconName.toLowerCase()] : '';
};

const selectContactMethod = (contactMethodId) => selectedContactMethod.value = contactMethodId;

const reactiveTour = computed(() => props.tour.id);

const reactiveContactMethod = computed(() => selectedContactMethod.value);

const form = useForm({
    tour_id: reactiveTour,
    contact_method: reactiveContactMethod,
    preferred_date: null,
    passenger: null,
    note: null,
    first_name: null,
    last_name: null,
    email: null,
    phone_number: null
});

</script>

<template>
    <MarginLayout>
        <div class="bg-white h-fit shadow py-6">
            <div class="max-w-sm lg:max-w-full lg:mx-16 flex items-center relative">
                <SvgLogo />
            </div>
        </div>

        <section class="my-8 mx-auto lg:flex xl:flex max-w-2xl xl:max-w-7xl">
            <Avatar icon="pi pi-user" class="mt-3 bg-white" size="large" shape="circle" />
            <div class="grow">
                <div class="mx-auto
                    max-w-sm
                    lg:max-w-lg
                    xl:max-w-4xl
                    bg-white shadow rounded py-6 px-5 relative"
                >
                    <!-- Title -->
                    <section>
                        <div class="flex items-center mb-6">
                            <div class="bg-white rotate-45 size-4 absolute xl:left-[-6px]"></div>
                            <h1>Hey there, We just need a few details to reserve this tour.</h1>
                        </div>
                        <!-- Reservation Detail -->
                        <form @submit.prevent="form.post(route('tour.submit-reservation'))">
                            <div class="mb-8 flex flex-col gap-2">
                                <h4>When do you want your tour to start? *</h4>
                                <Calendar v-model="form.preferred_date" dateFormat="dd MM yy" />
                                <InputError :message="form.errors.preferred_date" />
                            </div>
                            <div class="mb-8 flex flex-col gap-2">
                                <h4>How many people are traveling? *</h4>
                                <TextInput v-model="form.passenger" type="number" :error="form.errors.passenger" />
                            </div>
                            <div class="mb-8 flex flex-col gap-2">
                                <h2>Special Request & Questions</h2>
                                <textarea
                                    v-model="form.note"
                                    placeholder="Feel free to tell us anything you'd like or how you want to make the trip perfect for you. (optional)"
                                    class="rounded-md border-neutral-300 shadow"
                                ></textarea>
                                <InputError :message="form.errors.note" />
                            </div>
                            <div class="mb-8 flex flex-col gap-2">
                                <h2>Contact Info</h2>
                                <div class="flex gap-6 mt-4">
                                    <TextInput v-model="form.first_name" label="First Name *" :error="form.errors.first_name" />
                                    <TextInput v-model="form.last_name" label="Last Name *" :error="form.errors.last_name" />
                                </div>
                                <div class="flex gap-6 mt-4">
                                    <TextInput v-model="form.email" label="Email *" :error="form.errors.email" />
                                    <TextInput v-model="form.phone_number" label="Phone Number *" type="number" :error="form.errors.phone_number" />
                                </div>
                            </div>
                            <div class="mb-8 flex flex-col gap-2">
                                <h2>How should we contact you?</h2>
                                <div class="flex gap-4">
                                    <Button
                                        v-for="method in contact_methods"
                                        :key="method.id"
                                        :label="method.method_name"
                                        :icon="getContactMethodIcon(method.method_name)"
                                        plain text raised
                                        class="flex-1 shadow-none text-base outline outline-1 outline-gray-300 rounded hover:outline-indigo-500 hover:bg-white"
                                        :class="[{'border-primary bg-primary-100 text-black': selectedContactMethod === method.id}]"
                                        @click="selectContactMethod(method.id)"
                                    />
                                </div>
                                <InputError :message="form.errors.contact_method" />
                            </div>
                            <div class="flex place-content-end">
                                <Button type="submit" label="Reserve Now" />
                            </div>
                        </form>
                    </section>
                </div>
            </div>
            <div class="min-w-72 max-w-72 h-fit hidden xl:block">
                <div class="bg-white rounded-md shadow-md mb-8">
                    <img 
                        src="https://static.travelstride.com/store/51/ad4f08f81843578f3ea235b037593e/b669206bb8425f9409b22d1a87c8d181.jpg"
                        class="rounded-t-md"
                    >
                    <div class="p-3">
                        <h2>{{ tour.name }}</h2>
                        <div class="flex items-center justify-between">
                            <span class="text-xl font-medium">{{ tour.day }} Days</span>
                            <span class="text-xl font-medium"><small class="text-black">From</small> RM{{ tour.base_price }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </MarginLayout>
</template>