<script setup>
import ReservationForm from '@/Pages/Tour/Partials/ReservationForm.vue';
import ReservationDetailPanel from './Partials/ReservationDetailPanel.vue';

import Toast from 'primevue/toast';
import { useToast } from 'primevue/usetoast';

import { useForm, usePage } from '@inertiajs/vue3';

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

const page = usePage();
const toast = useToast();

const form = useForm({
    package_id: null,
    contact_method_id: null,
    discount_id: props.tour.discount?.id ?? null,
    pickup_location_id: null,
    preferred_date: null,
    adult: 0,
    child: 0,
    first_name: page.props.auth.user?.first_name ?? null,
    last_name: page.props.auth.user?.last_name ?? null,
    email: page.props.auth.user?.email ?? null,
    phone_number: page.props.auth.user?.phone_number ?? null,
    amount: null,
    note: null,
});

const handleToast = (severity, summary, detail, life = 6000) => {
    toast.add({ severity: severity, summary: summary, detail: detail, life: life })
}

const submitReservation = () => {
    form.post(route('reservation.store'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: (error) => handleToast(TOAST_ERROR, 'Something went wrong', 'Please double check your inputs and try again.'),
    });
};

function updateAmount(amount) {
    form.amount = amount;
}

</script>

<template>
    <main class="my-8 mx-auto lg:flex xl:flex max-w-2xl xl:max-w-7xl">
        <div class="flex w-full my-6 gap-6">
            <div class="w-full">
                 <div class="mx-auto grow max-w-full">

                    <ReservationForm 
                        :tour="tour" 
                        :form="form"
                        :contact_methods="contact_methods"
                    />
                    
                </div>
            </div>
            
            <div class="flex-col gap-6 min-w-96 max-w-96 h-fit hidden xl:flex sticky top-4">

                <ReservationDetailPanel 
                    :tour="tour"
                    :form="form"
                    @update-amount="updateAmount"
                    @reset-child="form.reset('child')"
                    @submit-reservation="submitReservation"
                />

            </div>
        </div>
    </main>
</template>