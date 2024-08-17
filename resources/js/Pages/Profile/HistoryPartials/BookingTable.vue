<script setup>
import { computed, ref } from 'vue';

import Button from 'primevue/button';
import Column from 'primevue/column';
import DataTable from 'primevue/datatable';
import Tag from 'primevue/tag';
import OverlayPanel from 'primevue/overlaypanel';
import Menu from 'primevue/menu';
import { useForm } from '@inertiajs/vue3';

const emit = defineEmits(['cancel-booking']);

const props = defineProps({
    bookings: {
        type: Object,
    },
});

const form = useForm({
    trip_status: null, 
});

const overlay = ref();

const items = (data) => {
    const status = ref(data.trip_status);

    const items = ref([
        {
            label: 'Cancel Booking',
            icon: 'pi pi-times',
            visible: () => status.value !== 'pending',
            command: () => cancelBooking(data), 
        }
    ]);

    return items.value;
};

function cancelBooking(booking) {
    form.trip_status = 'cancelled';

    form.patch(route('booking.update', { booking: booking.id }), {
        onSuccess: () => emit('cancel-booking', cancelBookingMessage),
    });
}

const cancelBookingMessage = {
    severity: 'info',
    summary: 'Booking Cancelled',
    detail: 'Your booking has been cancelled.',
    life: 6000,
};

const hasBookings = computed(() => props.bookings.length > 0);

const toggle = (event) => overlay.value.toggle(event);

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
                <Button
                    icon="pi pi-ellipsis-h"
                    text rounded
                    @click="toggle"
                />

                <OverlayPanel ref="overlay">
                    <Menu :model="items(data)" />
                </OverlayPanel>
            </Column>
        </DataTable>
    </div>
    <p v-else>Nothing here yet...</p>
</template>