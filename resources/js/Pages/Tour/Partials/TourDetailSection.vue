<script setup>
import Avatar from 'primevue/avatar';
import Accordion from 'primevue/accordion';
import AccordionTab from 'primevue/accordiontab';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Rating from 'primevue/rating';
import MeterGroup from 'primevue/metergroup';
import Button from 'primevue/button';

import TourContent from '@/Components/Tour/TourContent.vue';
import TourStepper from '@/Components/Tour/TourStepper.vue';
import TourIconBox from '@/Components/Tour/TourIconBox.vue';
import TourTextBox from '@/Components/Tour/TourTextBox.vue';

import { ref, computed } from 'vue';

import customParseFormat from 'dayjs/plugin/customParseFormat';
import dayjs from 'dayjs';
dayjs.extend(customParseFormat);

const emit = defineEmits(['reserve-tour', 'book-tour-date']);

const props = defineProps({
    tour: {
        type: Object,
        required: true,
    }
});

const maximumMeter = ref(0);
const averageRating = ref(0.0);

const rating = computed(() => {
    var reviewCount = 0, reviewRatings = 0;
    props.tour.reviews.forEach(review => {
        reviewCount++;
        reviewRatings += review.rating;
    });
    maximumMeter.value = reviewCount;
    averageRating.value = reviewRatings / reviewCount;

    return reviewCount != 0 ? reviewRatings / reviewCount : null; 
});

const formatRating = (rating) => {
    if (rating >= 4.0) {
        return `${rating} / 5 Excellent`;
    } else if (rating >= 3.0) {
        return `${rating} / 5 Good`;
    } else if (rating >= 2.0) {
        return `${rating} / 5 Average`;
    } else if (rating > 1.0) {
        return `${rating} / 5 Poor`;
    } else if (rating == 1) {
        return `${rating} / 5 Terrible`;
    }
};

const ratingMeterList = computed(() => {
    const ratingLabel = ['Terrible', 'Poor', 'Average', 'Good', 'Excellent'];
    const ratings = ref([]), reviewCount = [];

    props.tour.reviews.forEach(review => {
        if (reviewCount[review.rating] === undefined) reviewCount[review.rating] = 1;
        else reviewCount[review.rating] += 1; 
    });

    for(let i = ratingLabel.length; i > 0; i--) {
        ratings.value.push([{ label: ratingLabel[i - 1] !== undefined ? ratingLabel[i - 1] : 0, value: reviewCount[i], color: 'bg-primary' }]);
    }

    return ratings.value;
});

const getMeterLabel = meterData => {
    const rating = ref(meterData);
    return rating.value[0].label;
};

const getMeterValue = meterData => {
    const rating = ref(meterData);
    return rating.value[0].value !== undefined ? rating.value[0].value : 0;
};

const getPackageActivities = () => {
    var activities = [];

    props.tour.packages.forEach(item => {
        item.activities.forEach(activity => {
            if (!activities.find(a => a.name === activity.name)) { 
                activities.push(activity);
            }
        });
    });

    return activities;
}

</script>

<template>

    <div class="mx-auto h-full lg:w-5/6 xl:max-w-7xl mb-6">

        <TourContent id="detail" title="Details">
            <div class="flex gap-4">
                <TourIconBox>
                    <template #main>
                        <div class="flex gap-2">
                            <i class="pi pi-map-marker"></i>
                            <div class="flex flex-col">
                                <h3 class="font-black">Pick-up Location</h3>
                                <span
                                    v-for="pickupLocation in tour.pickup_location"
                                >
                                    {{ pickupLocation.location }}
                                </span>
                            </div>
                        </div>
                    </template>
                </TourIconBox>
            </div>
            <div>
                <h2>Description</h2>
                <p class="text-black text-justify">{{ tour.description }}</p>
            </div>
            <TourTextBox label="Destinations" :value="tour.destinations" />
            <TourTextBox label="Activities" :value="getPackageActivities()" />
            <div v-if="tour.highlights.length != 0" class="flex flex-col gap-2">
                <h2>Highlights</h2>
                <ul class="list-none leading-relaxed">
                    <li v-for="highlight in tour.highlights" class="flex gap-3 items-center">
                        <i class="pi pi-verified text-success"></i>
                        {{ highlight.description }}
                    </li>
                </ul>
            </div>
        </TourContent>

        <TourContent v-if="tour.itinerary.days.length != 0" id="itinerary" title="Itinerary">
            <TourStepper :days="tour.itinerary.days" />
        </TourContent>

        <!-- Customize -->
            <!-- <TourContent title="Customize Your Trip" titleCenter gradient>
            <div class="flex justify-between">
            </div>
            </TourContent> -->
            
        <TourContent id="available" title="Dates & Availability">
            <div v-if="tour.availabilities.length != 0">
                <DataTable :value="tour.availabilities" stripedRows tableStyle="max-width: 100%">
                    <Column header="Departure Date">
                        <template #body="{ data }">
                            <span>{{ dayjs(data.departure_date).format('DD MMM YYYY') }}</span>
                        </template>
                    </Column>
                    <Column header="Finished Date">
                        <template #body="{ data }">
                            <span>{{ dayjs(data.finished_date).format('DD MMM YYYY') }}</span>
                        </template>
                    </Column>
                    <Column header="Availability">
                        <template #body="{ data }">
                            <span v-if="data.maximum_slot > data.occupied_slot">{{ data.maximum_slot - data.occupied_slot }} slots left</span>
                            <h4 v-else class="text-error">Occupied</h4>
                        </template>
                    </Column>
                    <Column>
                        <template #body="{ data }">
                            <Button
                                label="Book"
                                :disabled="data.maximum_slot <= data.occupied_slot"
                                @click="emit('book-tour-date', data.pivot.availability_id)"
                                text rounded outlined
                            />
                        </template>
                    </Column>
                </DataTable>
                <div class="flex items-center mt-2">
                    <p>Prefer a specific date? Click</p>
                    <Button
                        label="Reserve"
                        @click="emit('reserve-tour')"
                        text
                    />
                    <p>to choose your preferred date.</p>
                </div>
            </div>
            <div v-else>
                <p>No available dates currently. Click <a href="#reserve" class="underline">'Reserve'</a> to reserve a slot for this tour.</p>
            </div>
        </TourContent>

        <TourContent id="notes" title="Notes">
            <Accordion :multiple="true">
                <AccordionTab v-for="subject in tour.note.subjects" :header="subject.name">
                    <ul v-for="bulletPoint in subject.bullet_points">
                        <li>{{ bulletPoint.content }}</li>
                    </ul>
                </AccordionTab>
            </Accordion>
        </TourContent>

        <TourContent id="review" title="Reviews & Ratings">
            <div v-if="tour.reviews.length != 0" class="flex gap-6">
                <div class="flex flex-col gap-3">
                    <Rating v-model="rating" readonly :cancel="false" class="flex place-items-start" />
                    <span class="flex-none text-xl font-bold">{{ formatRating(averageRating) }}</span>
                </div>
                <div class="px-6 w-full">
                    <MeterGroup
                        v-for="meterRating in ratingMeterList"
                        :value="meterRating"
                        :max="maximumMeter"
                        pt:root:class="flex gap-2 mb-6"
                    >
                        <template #label="slotProps">
                            <div class="flex justify-between">
                                <span>{{ getMeterLabel(slotProps.value) }}</span>
                                <span>{{ getMeterValue(slotProps.value) }}</span>
                            </div>
                        </template>
                    </MeterGroup>
                </div>
            </div>
            <div v-else>
                <p>No reviews currently.</p>
            </div>
        </TourContent>

        <div v-if="tour.reviews.length != 0" class="grid lg:grid-cols-2 gap-6 mt-6">
            <div v-for="review in tour.reviews" class="bg-white flex flex-col p-4 shadow rounded-lg">
                <div class="flex min-h-full">
                    <Avatar icon="pi pi-user" shape="circle" class="flex-none mr-3" />
                    <div class="p-1">
                        <h4>{{ review.user.name }}</h4>
                        <h3>{{ review.title }}</h3>
                        <p class="line-clamp-3">{{ review.content }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>