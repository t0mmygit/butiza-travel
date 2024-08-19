<script setup>
import { computed, ref } from 'vue';
import { useForm } from '@inertiajs/vue3';

import Column from 'primevue/column';
import DataTable from 'primevue/datatable';
import Tag from 'primevue/tag';
import SplitButton from 'primevue/splitbutton';
import { useConfirm } from 'primevue/useconfirm';

const emit = defineEmits(['cancel-reservation']);

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

const cancelReservationMessage = {
    severity: 'info',
    summary: 'Reservation Cancelled',
    detail: 'Your reservation has been cancelled.',
    life: 6000,
};

function cancelReservation(reservation) {
    form.status = 'cancelled';
    
    form.patch(route('reservation.update', { reservation: reservation.id }), {
        onSuccess: () => 
        emit('cancel-reservation', cancelReservationMessage),
    });
}

const isCancelled = (status) => status === 'cancelled';

const hasReservations = computed(() => props.reservations.length > 0);

</script>

<template>
    <div v-if="hasReservations" class="border border-surfaceBorder rounded sm:rounded-md">
        <DataTable :value="reservations">
            <Column field="reference" header="Reservation. No" />
            <Column field="package.tour.name" header="Tour Name" />
            <Column field="preferred_date" header="Preferred Date" />
            <Column header="Status">
                <template #body="{ data }">
                    <Tag :value="data.status" severity="info" />
                </template>
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