<script setup>
import { DEFAULT_DATE_FORMAT, TOAST_INFO, TOAST_ERROR } from '@/constant';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';

import Button from 'primevue/button';
import Column from 'primevue/column';
import DataTable from 'primevue/datatable';
import Dialog from 'primevue/dialog';
import Rating from 'primevue/rating';
import Tag from 'primevue/tag';
import SplitButton from 'primevue/splitbutton';
import { useConfirm } from 'primevue/useconfirm';

import { computed, ref } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import dayjs from 'dayjs';

const emit = defineEmits(['cancel-booking', 'review-booking', 'tour-direct-failed']);

const props = defineProps({
    bookings: {
        type: Object,
    },
});

const visibleReview = ref(false);
const booking = ref(null);
const confirm = useConfirm();

const form = useForm({
    trip_status: null, 
});

const reviewForm = useForm({
    title: null,
    content: null,
    rating: 0,
});

const items = (data) => {
    const status = ref(data.trip_status);

    switch (status.value) {
        case 'pending':
            return pendingItems(data);
        case 'confirmed':
            return confirmedItems(data);
        case 'completed':
            return completedItems(data);
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

const completedItems = (data) => {
    return [
        {
            label: 'Give Review',
            icon: 'pi pi-star',
            command: () => makeReview(data),
        }
    ];
}

const makeReview = (data) => {
    visibleReview.value = true;
    booking.value = data;
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

function createMessage(severity, summary, detail, life) {
    return {
        severity,
        summary,
        detail,
        life,
    };
}

function handleStatusSeverity(status) {
    switch (status) {
        case 'pending':
            return 'warning';
        case 'confirmed':
            return 'info';
        case 'completed':
            return 'success';
        case 'cancelled':
            return 'danger';
    }
}

function cancelBooking(booking) {
    form.trip_status = 'cancelled';

    form.patch(route('booking.update', { booking: booking.id }), {
        onSuccess: () => emit('cancel-booking', cancelBookingMessage)
    });
}

function cancelReviewing() {
    reviewForm.reset();
    booking.value = null;
    visibleReview.value = false;
}

function submitReview() {
    reviewForm.post(route('review.store', { tour: booking.value.package.tour.slug, booking: booking.value.id, }), {
        onSuccess: () => {
            reviewForm.reset();
            booking.value = null;
            visibleReview.value = false;
            emit('review-booking', reviewBookingMessage)
        },
    });
}

const directToTour = tour => {
    router.get(route('tour.show', { tour: tour.slug }), {
        onError: (error) => emit('tour-direct-failed', failedDirectTourMessage),
    })
};

const formatDate = date => dayjs(date).format(DEFAULT_DATE_FORMAT);
const cancelBookingMessage = createMessage(TOAST_INFO, 'Booking Cancelled', 'Your booking has been cancelled.', 6000);
const reviewBookingMessage = createMessage(TOAST_INFO, 'Review Submitted', 'Your review has been submitted.', 6000);
const failedDirectTourMessage = createMessage(TOAST_ERROR, 'Redirection Failed', 'Please try again later.', 6000);
const isCancelled = status => status === 'cancelled';
const isReviewable  = review => review === null;
const hasBookings = computed(() => props.bookings.length > 0);

</script>

<template>
    <div v-if="hasBookings" class="border border-surfaceBorder rounded sm:rounded-lg">
        <DataTable 
            :value="bookings" 
            paginator 
            :rows="5" :rowsPerPageOptions="[5, 10, 20, 50]"
        >
            <Column field="reference" header="Reference. No" />
            <Column header="Tour Name" #body="{ data }">
                <a 
                    href="#" 
                    @click="directToTour(data.package.tour)" 
                    class="hover:underline"
                >
                    {{ data.package.tour.name }}
                </a>
            </Column>
            <Column header="Status">
                <template #body="{ data }">
                    <Tag 
                        :value="data.trip_status"
                        :severity="handleStatusSeverity(data.trip_status)"
                        class="font-bold"
                    />
                </template>
            </Column>
            <Column header="Departure Date" #body="{ data }">
                {{ formatDate(data.departure_date) }}
            </Column>
            <Column header="Finished Date" #body="{ data }">
                {{ formatDate(data.finished_date) }}
            </Column>
            <Column #body="{ data }">
                <SplitButton
                    v-if="!isCancelled(data.trip_status) && isReviewable(data.review)" 
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

    <Dialog v-model:visible="visibleReview" header="Give Review" modal :closable="false" :closeOnEscape="false"  class="md:w-96">
        <template #header>
            <h3>Review</h3>
        </template>
        <div class="flex flex-col mb-8">
            <span class="font-bold">How was your adventure?</span>
            <small>Reviewing for {{ booking.package.tour.name }} ({{ booking.reference }})</small>
        </div>
        <form>
            <div class="flex flex-col space-y-3">
                <div>
                    <div :class="['p-inputtext bg-white flex justify-center items-center py-3 rounded-md', { 'border-inputError transition-none': reviewForm.errors.rating }]">
                        <Rating
                            v-model="reviewForm.rating"
                            :cancel="false"
                        />
                    </div>
                    <InputError class="mt-2" :message="reviewForm.errors.rating" />
                </div>
                <TextInput 
                    v-model="reviewForm.title" 
                    label="Title"
                    :error="reviewForm.errors.title"
                />
                <TextInput 
                    v-model="reviewForm.content" 
                    type="textarea" 
                    label="Your Review"
                    :error="reviewForm.errors.content"
                />
            </div>
        </form>
        <template #footer>
            <Button 
                label="Cancel" 
                severity="secondary" 
                text outlined 
                @click="cancelReviewing()"
            />
            <Button 
                label="Submit Review"
                @click="submitReview()"
                autofocus
            />
        </template>
    </Dialog>
</template>