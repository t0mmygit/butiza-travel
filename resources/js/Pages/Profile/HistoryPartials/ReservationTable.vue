<script setup>
import { DEFAULT_DATE_FORMAT, TOAST_ERROR, TOAST_INFO, TOAST_SUCCESS, TOAST_WARNING } from '@/constant';
import { useFormatPrice } from '@/Composables/formatPrice';
import { computed, ref } from 'vue';
import { router, useForm } from '@inertiajs/vue3';

import Column from 'primevue/column';
import DataTable from 'primevue/datatable';
import Tag from 'primevue/tag';
import SplitButton from 'primevue/splitbutton';
import { useConfirm } from 'primevue/useconfirm';
import dayjs from 'dayjs';

const emit = defineEmits(['cancel-reservation', 'tour-direct-failed']);

const props = defineProps({
    reservations: {
        type: Object,
    },
});

const confirm = useConfirm();

const form = useForm({
    status: null,
});

const pendingItems = (data) => {
    return [
        {
            label: 'Cancel Reservation',
            icon: 'pi pi-times',
            command: () => confirmCancelReservation(data), 
        }
    ]
}

const items = (data) => {
    const status = ref(data.status);

    switch (status.value) {
        case 'pending':
            return pendingItems(data);
    }
}

const confirmCancelReservation = (reservation) => {
    confirm.require({
        message: 'Confirm you want to cancel this reservation?',
        header: 'Confirmation',
        icon: 'pi pi-info-circle',
        acceptLabel: 'Confirm',
        rejectLabel: 'Cancel',
        acceptClass: 'p-button-danger',
        rejectClass: 'p-button-secondary',
        accept: () => cancelReservation(reservation),
        reject: () => {},
    });
}

function createMessage(severity, summary, detail, life) {
    return {
        severity,
        summary,
        detail,
        life,
    };
}

function cancelReservation(reservation) {
    form.status = 'cancelled';
    
    form.patch(route('reservation.update', { reservation: reservation.id }), {
        onSuccess: () => emit('cancel-reservation', cancelReservationMessage),
    });
}

const directToTour = tour => {
    router.get(route('tour.show', { tour: tour.slug }), {
        onError: (error) => emit('tour-direct-failed', failedDirectTourMessage),
    })
};

function handleStatusSeverity(status) {
    switch (status) {
        case 'completed':
            return TOAST_SUCCESS;
        case 'pending':
            return TOAST_WARNING;
        case 'cancelled':
            return TOAST_ERROR;
        case 'rejected':
            return TOAST_ERROR;
        default:
            return TOAST_INFO;
    }
}

const cancelReservationMessage = createMessage(TOAST_INFO, 'Reservation Cancelled', 'Your reservation has been cancelled.', 6000);
const failedDirectTourMessage = createMessage(TOAST_ERROR, 'Redirection Failed', 'Please try again later.', 6000);
const displayAmount = amount => useFormatPrice(parseFloat(amount), 2, false)
const isCancelled = status => status === 'cancelled';
const hasReservations = computed(() => props.reservations.length > 0);

</script>

<template>
    <div v-if="hasReservations" class="border border-surfaceBorder rounded sm:rounded-md">
        <DataTable :value="reservations">
            <Column field="reference" header="Reservation. No" />
            <Column header="Tour Name" #body="{ data }">
                <a 
                    href="#" 
                    @click="directToTour(data.package.tour)" 
                    class="hover:underline"
                >
                    {{ data.package.tour.name }}
                </a>
            </Column>
            <Column header="Preferred Date" #body="{ data }">
                {{ dayjs(data.preferred_date).format(DEFAULT_DATE_FORMAT) }}
            </Column>
            <Column header="Amount" #body="{ data }">
                {{ displayAmount(data.amount) }}
            </Column>
            <Column header="Status" #body="{ data }">
                <Tag :value="data.status" :severity="handleStatusSeverity(data.status)" />
            </Column>
            <Column #body="{ data }">
                <SplitButton
                    v-if="!isCancelled(data.status)"
                    :model="items(data)"
                    menuButtonIcon="pi pi-ellipsis-h"
                    pt:button:root:class="hidden"
                    pt:menuButton:root:class="rounded-full"
                    text
                />
            </Column>
        </DataTable>
    </div>
    <p v-else>Nothing here yet...</p>
</template>