<script setup>
import { useFormatPrice } from '@/Composables/formatPrice';
import { useFormatText } from '@/Composables/formatText';
import { router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import dayjs from 'dayjs';

import Column from 'primevue/column';
import DataTable from 'primevue/datatable';
import Tag from 'primevue/tag';
import ModalMessage from '@/Components/Modal/ModalMessage.vue';

const emit = defineEmits(['open-review-dialog']);

const props = defineProps({
    bookings: {
        type: Object,
    },
});

const isVisible = ref(false);

const hasBookings = computed(() => props.bookings.length > 0);

</script>

<template>
    <!-- <ModalMessage
        :show="isVisible"
        type="cancel"
        title="Cancel Booking"
        @cancel="isVisible = false"
        @close="isVisible = false"
        @trigger-action="cancelBooking"
    /> -->
    <div v-if="hasBookings" class="border border-surfaceBorder rounded sm:rounded-lg">
        <DataTable :value="bookings">
            <Column field="id" header="Booking. No" />
            <Column field="package.tour.name" header="Tour Name" />
            <Column header="Status">
                <template #body="{ data }">
                    <Tag :value="data.trip_status" severity="info" />
                </template>
            </Column>
            <Column field="departure_date" header="Departure Date" />
            <Column field="finished_date" header="Finished Date" />
        </DataTable>
    </div>
    <p v-else>Nothing here yet...</p>
</template>