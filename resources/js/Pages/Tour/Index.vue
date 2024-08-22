<script setup>
import MarginLayout from '@/Layouts/MarginLayout.vue'; 
import TourCarouselSection from '@/Pages/Tour/Partials/TourCarouselSection.vue';
import TourDetailSection from '@/Pages/Tour/Partials/TourDetailSection.vue';

import NavBar from '@/Components/NavBar.vue';
import Footer from '@/Components/Footer.vue';
import Toast from 'primevue/toast';
import { useToast } from 'primevue/usetoast';

import { router } from '@inertiajs/vue3';
import customParseFormat from 'dayjs/plugin/customParseFormat';
import dayjs from 'dayjs';
import { TOAST_ERROR } from '@/constant';
dayjs.extend(customParseFormat);

const props = defineProps({
    tour: {
        type: Object,
        required: true,
    }
});

const toast = useToast();

const bookTourDate = (availabilityId) => {
    router.get(route('booking.create', { tour: props.tour.slug, availability: availabilityId })), {
        onError: (error) => handleToast(TOAST_ERROR, 'Error', `Unable to book tour date! Please contact support.`),
    };
};

const reserveTour = () => {
    router.get(route('reservation.create', { tour: props.tour.slug }), {
        onError: (error) => handleToast(TOAST_ERROR, 'Error', 'Unable to reserve tour! Please contact support.'),
    });
};

function handleToast(severity, summary, detail, life = 6000) {
    toast.add({ severity: severity, summary: summary, detail: detail, life: life })
}

</script>

<template>
    <MarginLayout>
        <Toast class="z-50" />
        <NavBar />

        <TourCarouselSection 
            :tour="tour" 
            @reserve-tour="reserveTour"
        />

        <TourDetailSection 
            :tour="tour" 
            @reserve-tour="reserveTour"
            @book-tour-date="bookTourDate"
        />
        
        <Footer />
    </MarginLayout>
</template>