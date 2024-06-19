<script setup>
import { useFormatPrice } from '@/Composables/formatPrice';
import ProfileLayout from '@/Layouts/ProfileLayout.vue';
import ReviewForm from '@/Components/Dialog/ReviewForm.vue';
import BookingTable from '@/Pages/Profile/HistoryPartials/BookingTable.vue';

import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import Tag from 'primevue/tag';
import Divider from 'primevue/divider';
import Dialog from 'primevue/dialog';

import { ref } from 'vue';
import dayjs from 'dayjs';

const props = defineProps({
    bookings: {
        type: [Array, Object],
    },
    reviews: {
        type: [Array, Object],
    },
    bookingStatuses: {
        type: [Array, Object],
    },
    reservations: {
        type: [Array, Object],
    },
    payments: {
        required: false,
    }
});

const reviewDialog = ref(true);
const booking = ref(null);

</script>

<template>
    <Dialog v-model:visible="reviewDialog" modal header="Write a review" style="min-width: 30rem">
        <ReviewForm
            :booking="booking" 
            @close-review-dialog="reviewDialog = false"
        />
    </Dialog>

    <ProfileLayout section="History">
        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <section role="booking" class="mb-8">
                    <h1 class="mb-4">Booking History</h1>
                    <div v-if="bookings.length != 0" class="bg-white shadow sm:rounded-lg">
                        <BookingTable 
                            :bookings="bookings" 
                            :bookingStatuses="bookingStatuses" 
                            :reviews="reviews"
                            @open-review-dialog="reviewDialog = true"
                        />
                    </div>
                    <div v-else class="sm:p-4 bg-white shadow sm:rounded-lg">
                        <p>No booking history...</p>
                    </div>
                </section>
                <section role="reservation">
                    <h1 class="mb-4">Reservation History</h1>
                    <div v-if="reservations.length != 0" class="bg-white shadow sm:rounded-lg">
                        <DataTable :value="reservations" dataKey="id" tableStyle="min-width: 50rem">
                            <Column expander style="width: 5rem" />
                            <Column header="Date">
                                <template #body="{ data }">
                                    <span>{{ dayjs(data.created_at).format('DD MMM YYYY') }}</span>
                                </template>
                            </Column>
                            <Column field="tour.name" header="Tour Name" />
                            <Column header="Status">
                                <template #body="{ data }">
                                    <Tag severity="danger" value="Pending" />
                                </template>
                            </Column>
                            <Column header="Action" class="flex items-center justify-center">
                                <template #body="{ data, index }">
                                    <Button severity="danger" />
                                </template>
                            </Column>
                            <template #expansion="slotProps">
                                <div class="p-3">
                                    <h3>Reservation Details</h3>
                                </div>
                            </template>
                        </DataTable>
                    </div>
                    <div v-else class="sm:p-4 bg-white shadow sm:rounded-lg">
                        <p>No reservation history...</p>
                    </div>
                </section>
                <section role="reservation">
                    <h1 class="mb-4">Payment History</h1>
                    <div class="sm:p-4 bg-white shadow sm:rounded-lg">
                        <p>No payment history...</p>
                    </div>
                </section>
            </div>
        </div>
    </ProfileLayout>
</template>