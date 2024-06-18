<script setup>
import ProfileLayout from '@/Layouts/ProfileLayout.vue';
import ReviewForm from '@/Components/Dialog/ReviewForm.vue';
import { useFormatPrice } from '@/Composables/formatPrice';

import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import Tag from 'primevue/tag';
import Divider from 'primevue/divider';
import Dialog from 'primevue/dialog';

import { ref, computed } from 'vue';
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

const reviewDialog = ref(false);
const booking = ref(null);
const expandedRows = ref({});

const bookingDetails = [
    {
        title: 'Tour ID',
        value: (booking) => booking.tour.id,
    },
    {
        title: 'Departure Date',
        value: (booking) => dayjs(booking.departure_date).format('DD MMMM YYYY'),
    },
    {
        title: 'Guide Type',
        value: (booking) => 'No Guide Type Yet'
    },
    {
        title: 'Duration',
        value: (booking) => `${booking.tour.day} Days`,
    },
    {
        title: 'Number of Passengers',
        value: (booking) => booking.passenger,
    },
    {
        title: 'Destinations',
        value: (booking) => formatDestination(booking.tour.destinations),
    },
];

const formatDestination = destinations => destinations.map(destination => destination.name).join(', ');

function triggerReviewDialog(bookingData) {
    booking.value = bookingData;
    reviewDialog.value = true;
}

function actionOption(booking) {
    const index = props.reviews.findIndex(review => review.id === booking.id);
    if (index != -1) return false;
    else return true;  
}

const getBookingSeverity = (statusName) => {
    const bookingStatus = props.bookingStatuses.find(status => status.name === statusName);
    return bookingStatus.severity;
};

const getBookingValue = (statusName) => {
    const bookingStatus = props.bookingStatuses.find(status => status.name === statusName);
    return bookingStatus.value;
};

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
                        <DataTable v-model:expandedRows="expandedRows" :value="bookings" dataKey="id" tableStyle="min-width: 50rem">
                            <Column expander style="width: 5rem" />
                            <Column header="Date">
                                <template #body="{ data }">
                                    <span>{{ dayjs(data.created_at).format('DD MMM YYYY') }}</span>
                                </template>
                            </Column>
                            <Column header="Tour Name">
                                <template #body="{ data }">
                                    <span class="hover:underline cursor-pointer" @click="$inertia.get(route('tour.show', { tour: data.tour.id }))">
                                        {{ data.tour.name }}
                                    </span>
                                </template>
                            </Column>
                            <Column header="Total Price">
                                <template #body="{ data }">
                                    <span>{{ useFormatPrice(data.passenger * data.tour.base_price) }}</span>
                                </template>
                            </Column>
                            <Column header="Status">
                                <template #body="{ data }"> 
                                    <Tag 
                                        :severity="getBookingSeverity(data.status)" 
                                        :value="getBookingValue(data.status)" 
                                    />
                                </template>
                            </Column>
                            <Column>
                                <template #body="{ data }">
                                    <div v-if="actionOption(data)">
                                        <a v-if="data.status === 'confirmed'" class="group text-sm font-bold text-red-500 flex justify-center items-center gap-2">
                                            <i class="pi pi-times-circle"></i>
                                            <span class="group-hover:underline">Cancel</span>
                                        </a>
                                        <a v-else-if="data.status !== 'cancelled' && data.status !== 'confirmed'" class="group text-sm font-bold text-orange-500 flex justify-center items-center gap-2" @click="triggerReviewDialog(data)">
                                            <i class="pi pi-pen-to-square"></i>
                                            <span class="group-hover:underline">Review</span>
                                        </a>
                                    </div>
                                </template>
                            </Column>
                            <template #expansion="slotProps">
                                <div class="p-3">
                                    <h3 class="mb-4">Booking Details</h3>   

                                    <div class="shadow py-3 px-6 outline outline-1 outline-neutral-300 rounded text-sm">
                                        <div class="flex space-x-8 justify-between">
                                            <div v-for="(detail, index) in bookingDetails" :key="index" class="flex flex-col text-sm">
                                                <strong>{{ detail.title }}</strong>
                                                <span v-html="detail.value(slotProps.data)"></span>
                                            </div>
                                        </div>
                                        <Divider />
                                        <div class="flex flex-col">
                                            <strong class="mb-4">Itinerary</strong>
                                            <div v-for="day in slotProps.data.tour.itineraries.days" class="flex flex-col">
                                                <span><strong>Day {{ day.day_number }}</strong> {{ day.day_title }}</span>
                                            </div>
                                        </div>
                                        <Divider />
                                        <div class="flex flex-col">
                                            <strong class="mb-4">Payment Details</strong>
                                            <span>No Payment Info</span>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </DataTable>
                    </div>
                    <div v-else class="sm:p-4 bg-white shadow sm:rounded-lg">
                        <p>No booking history...</p>
                    </div>
                </section>
                <section role="reservation">
                    <h1 class="mb-4">Reservation History</h1>
                    <div v-if="reservations.length != 0" class="bg-white shadow sm:rounded-lg">
                        <DataTable v-model:expandedRows="expandedRows" :value="reservations" dataKey="id" tableStyle="min-width: 50rem">
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