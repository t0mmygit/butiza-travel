<script setup>
import MarginLayout from '@/Layouts/MarginLayout.vue';
import BookingForm from '@/Pages/Tour/Partials/BookingForm.vue';
import { watch } from 'vue';
import Toast from 'primevue/toast';
import { useToast } from 'primevue/usetoast';

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
    },
    flash: {
        type: Object,
    },
});

const toast = useToast();

watch(() => props.flash, (newValue) => {
    if (newValue?.status != null && newValue?.message != null) {
        toast.add({
            severity: newValue.status,
            summary: newValue.status,
            detail: newValue.message,
            life: 3000,
        });
    }
});

</script>

<template>
    <Toast class="z-50" />
    <MarginLayout>
        <div class="bg-white h-fit shadow py-6">
            <div class="flex items-center mx-auto w-11/12 lg:w-5/6 xl:max-w-7xl">
                <ApplicationLogo />
            </div>
        </div>

        <section class="my-8 mx-auto lg:flex w-11/12 lg:w-5/6 xl:max-w-7xl">
            <BookingForm 
                :tour="tour" 
                :availability="availability"
                :contact_methods="contact_methods"
            />
        </section>
    </MarginLayout>
</template>