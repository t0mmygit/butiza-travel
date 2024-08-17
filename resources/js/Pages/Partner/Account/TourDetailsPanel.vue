<script setup>
import Panel from 'primevue/panel';
import InlineMessage from 'primevue/inlinemessage';
import {  useFormatPrice } from '@/Composables/formatPrice';
import dayjs from 'dayjs';
import { computed } from 'vue';

const props = defineProps({
    tour: {
        type: [Object, null],
    },
    tourPackage: {
        type: [Object, null],
    },
    departureDate: {
        type: [Date, null],
    },
    finishedDate: {
        type: [Date, null],
    },
});

const displayTourDuration = computed(() => {
    return props.tour?.duration > 1
            ? `${props.tour?.duration} days`
            : `${props.tour?.duration} day`;
});

const displayPackagePrice = computed(() => useFormatPrice(props.tourPackage?.price));
const displayDepartureDate = computed(() => dayjs(props.departureDate).format('DD MMMM YYYY'));
const displayFinishedDate = computed(() => dayjs(props.finishedDate).format('DD MMMM YYYY'));

const hasTour = computed(() => props.tour != null);
const hasPackage = computed(() => props.tourPackage != null);

</script>

<template>
    <Panel header="Tour Details" class="flex-1">
        <InlineMessage v-if="!hasTour" severity="secondary">
            Your tour details will be displayed here.
        </InlineMessage>
        <div v-else class="flex flex-col gap-3">
            <div class="flex flex-col">
                <strong>{{ tour?.name }}</strong>
                <span>{{ displayTourDuration }}</span>
            </div>

            <div v-if="hasPackage" class="flex flex-col">
                <strong>{{ tourPackage?.name }}</strong>
                <span>{{ displayPackagePrice }}</span>
            </div>

            <div v-if="departureDate" class="flex flex-col">
                <strong>Departure Date</strong>
                <span>{{ displayDepartureDate }}</span>
            </div>

            <div v-if="finishedDate" class="flex flex-col">
                <strong>Finished Date</strong>
                <span>{{ displayFinishedDate }}</span>
            </div>
        </div>
    </Panel>
</template>