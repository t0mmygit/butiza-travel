<script setup>
import MarginLayout from '@/Layouts/MarginLayout.vue';
import PaymentForm from '@/Pages/Tour/Partials/PaymentForm.vue';
import BookingForm from '@/Pages/Tour/Partials/BookingForm.vue';

import { ref } from 'vue';

const paymentSection = ref(false);

const props = defineProps({
    tour: {
        type: Object,
        required: true
    },
    availability: {
        type: Object,
        required: true,
    },
    contact_methods: {
        type: Object,
        required: true
    }
});

</script>

<template>
    <MarginLayout>
        <div class="bg-white h-fit shadow py-6">
            <div class="flex items-center mx-auto w-11/12 lg:w-5/6 xl:max-w-7xl">
                <ApplicationLogo />
            </div>
        </div>

        <section class="my-8 mx-auto lg:flex w-11/12 lg:w-5/6 xl:max-w-7xl">
            <BookingForm 
                v-if="!paymentSection" 
                :tour="tour" 
                :availability="availability"
                :contact_methods="contact_methods"
            />

            <PaymentForm v-else />
        </section>
    </MarginLayout>
</template>