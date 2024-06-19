    <script setup>
import { useFormatPrice } from '@/Composables/formatPrice';
import { ref } from 'vue';
import dayjs from 'dayjs';

import Column from 'primevue/column';
import DataTable from 'primevue/datatable';
import Divider from 'primevue/divider';
import Tag from 'primevue/tag';
import { router } from '@inertiajs/vue3';

const emit = defineEmits(['open-review-dialog']);

const props = defineProps({
    bookings: {
        type: [Array, Object],
    },
    bookingStatuses: {
        type: [Array, Object],
    },
    reviews: {
        type: [Array, Object],
    }
});

const booking = ref(null);
const expandedRows = ref({});
const reviewDialog = ref(false);

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

function review(bookingData) {
    var array = [];
    switch(bookingData.status) {
        case 'confirmed':
            array['label'] = 'Cancel';
            array['status'] = 'confirmed';
            array['icon'] = 'pi pi-times-circle';
            array['action'] = (bookingData) => router.patch(route('booking.update', { booking: bookingData.id}))
            array['class'] = 'group text-sm font-bold text-red-500 flex justify-center items-center gap-2';
            break;
        case 'completed':
            const index = props.reviews.findIndex(review => review.booking_id === bookingData.id);
            if (index !== -1) break;
            array['label'] = 'Review';
            array['status'] = 'completed';
            array['icon'] = 'pi pi-pen-to-square';
            array['action'] = (bookingData) => emit('open-review-dialog', bookingData);
            array['class'] = 'group text-sm font-bold text-orange-500 flex justify-center items-center gap-2';
            break;
    }
    return array;
}

function rowsPerPageOptions(length) {
    var pageOptions = ref([]);
    const divisor = 5; const dividend = length; 
    const quotient = dividend / divisor;
    for(var i = 1; i < quotient; i++) { pageOptions.value.push(i * divisor); }
    return pageOptions.value;
}

const getBookingValue = statusName => props.bookingStatuses.find(status => status.name === statusName).value;
const getBookingSeverity = statusName => props.bookingStatuses.find(status => status.name === statusName).severity;
const formatDestination = destinations => destinations.map(destination => destination.name).join(', ');

</script>

<template>
    <DataTable
        v-model:expandedRows="expandedRows" dataKey="id"
        :value="bookings" paginator
        :rows="bookings.length < 5 ? bookings.length : 5"
        :rowsPerPageOptions="rowsPerPageOptions(bookings.length)"
        tableStyle="min-wdith: 50rem"
    >
        <Column expander style="width: 5rem" />
        <Column header="Date">
            <template #body="{ data }">
                <span>{{ dayjs(data.created_at).format('DD MMM YYYY') }}</span>
            </template>
        </Column>
        <Column header="Tour">
            <template #body="{ data }">
                <span 
                    class="hover:underline cursor-pointer"
                    @click="$inertia.get(route('tour.show', { tour: data.tour.id }))"
                >{{ data.tour.name }}
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
                <a :class="review(data)['class']">
                    <i :class="review(data)['icon']"></i>
                    <span class="group-hover:underline">
                        {{ review(data)['label'] }}
                    </span>
                </a>
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
</template>