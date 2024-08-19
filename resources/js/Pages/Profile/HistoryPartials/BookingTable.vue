<script setup>
import { computed, ref } from 'vue';
import { useForm } from '@inertiajs/vue3';

import Column from 'primevue/column';
import DataTable from 'primevue/datatable';
import Tag from 'primevue/tag';
import SplitButton from 'primevue/splitbutton';
import { useConfirm } from 'primevue/useconfirm';

const emit = defineEmits(['cancel-booking']);

const props = defineProps({
    bookings: {
        type: Object,
    },
    statuses: {
        type: Object,
    }
});

const confirm = useConfirm();

const form = useForm({
    trip_status: null, 
});

const items = (data) => {
    const status = ref(data.trip_status);

    switch (status.value) {
        case 'pending':
            return pendingItems(data);
        case 'confirmed':
            return confirmedItems(data);
    }
};

const pendingItems = (data) => {
    return [
        {
            label: 'Cancel Booking',
            icon: 'pi pi-times',
            command: () => confirmCancelBooking(data), 
        }
    ];
}

const confirmedItems = (data) => {
    return [
        {
            label: 'Cancel Booking',
            icon: 'pi pi-times',
            command: () => { 
                confirmCancelBooking(data)
            }, 
        }
    ];
}

const confirmCancelBooking = (booking) => {
    // TODO: Dynamic Message
    confirm.require({
        message: 'Confirm you want to cancel this booking?',
        header: 'Confirmation',
        icon: 'pi pi-info-circle',
        acceptLabel: 'Confirm',
        rejectLabel: 'Cancel',
        acceptClass: 'p-button-danger',
        rejectClass: 'p-button-secondary',
        accept: () => cancelBooking(booking),
        reject: () => {},
    });
}

const cancelBookingMessage = {
    severity: 'info',
    summary: 'Booking Cancelled',
    detail: 'Your booking has been cancelled.',
    life: 6000,
};

function cancelBooking(booking) {
    form.trip_status = 'cancelled';

    form.patch(route('booking.update', { booking: booking.id }), {
        onSuccess: () => {
            emit('cancel-booking', cancelBookingMessage)
        },
    });
}

const isCancelled = (status) => status === 'cancelled';

const hasBookings = computed(() => props.bookings.length > 0);

</script>

<template>
    <div v-if="hasBookings" class="border border-surfaceBorder rounded sm:rounded-lg">
        <DataTable :value="bookings">
            <Column field="reference" header="Reference. No" />
            <Column field="package.tour.name" header="Tour Name" />
            <Column header="Status">
                <template #body="{ data }">
                    <Tag :value="data.trip_status" severity="info" />
                </template>
            </Column>
            <Column field="departure_date" header="Departure Date" />
            <Column field="finished_date" header="Finished Date" />
            <Column #body="{ data }">
                <SplitButton
                    v-if="!isCancelled(data.trip_status)"
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