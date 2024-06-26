<script setup>
import { useFormatPrice } from '@/Composables/formatPrice';
import ProfileLayout from '@/Layouts/ProfileLayout.vue';
import ReviewForm from '@/Components/Dialog/ReviewForm.vue';
import BookingTable from '@/Pages/Profile/HistoryPartials/BookingTable.vue';

import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import Tag from 'primevue/tag';
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

const reservationDetails = [
    {
        title: 'Tour ID',
        value: (reservation) => reservation.tour.id,   
    },
    {
        title: 'Preferred Date',
        value: (reservation) => dayjs(reservation.preferred_date).format('DD MMMM YYYY'),
    },
    {
        title: 'Number of Passenger',
        value: (reservation) => reservation.passenger,
    }
];

// function reservationDetails() {
//     const tour = [
//         {
//             title: 'Tour ID',
//             value: (reservation) => reservation.tour.id,   
//         },
//         {
//             title: 'Preferred Date',
//             value: (reservation) => dayjs(reservation.preferred_date).format('DD MMMM YYYY'),
//         },
//         {
//             title: 'Number of Passenger',
//             value: (reservation) => reservation.passenger,
//         }
//     ]
// }

function openReviewDialog(bookingData) {
    booking.value = bookingData;
    reviewDialog.value = true;
}

const reviewDialog = ref(false);
const booking = ref(null);
const expandedRows = ref({});

function rowsPerPageOptions(length) {
    var pageOptions = ref([]);
    const divisor = 5; const dividend = length; 
    const quotient = dividend / divisor;
    for(var i = 1; i < quotient; i++) { pageOptions.value.push(i * divisor); }
    return pageOptions.value;   
}

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
                            @open-review-dialog="openReviewDialog"
                        />
                    </div>
                    <div v-else class="sm:p-4 bg-white shadow sm:rounded-lg">
                        <p>No booking history...</p>
                    </div>
                </section>
                <section role="reservation">
                    <h1 class="mb-4">Reservation History</h1>
                    <div v-if="reservations.length != 0" class="bg-white shadow sm:rounded-lg">
                        <DataTable v-model:expandedRows="expandedRows" dataKey="id" 
                                    :value="reservations" paginator
                                    :rows="reservations.length < 5 ? reservations.length : 5"
                                    :rowsPerPageOptions="rowsPerPageOptions(reservations.length)" 
                                    tableStyle="min-width: 50rem">
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
                            <!-- <Column header="Action" class="flex items-center justify-center">
                                <template #body="{ data, index }">
                                    <Button severity="danger" />
                                </template>
                            </Column> -->
                            <template #expansion="slotProps">
                                <div class="p-3">
                                    <h3 class="mb-4">Reservation Details</h3>
                                    <div class="shadow py-3 px-6 outline outline-1 outline-neutral-300 rounded text-sm">
                                        <div class="flex space-x-8">
                                            <div v-for="(detail, index) in reservationDetails" :key="index" class="flex flex-col text-sm">
                                                <strong>{{ detail.title }}</strong>
                                                <span v-html="detail.value(slotProps.data)"></span>
                                            </div>
                                        </div>
                                    </div>
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