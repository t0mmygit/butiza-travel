<script setup>
import MarginLayout from '@/Layouts/MarginLayout.vue'; 

import Avatar from 'primevue/avatar';
import Accordion from 'primevue/accordion';
import AccordionTab from 'primevue/accordiontab';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Rating from 'primevue/rating';
import MeterGroup from 'primevue/metergroup';
import Image from 'primevue/image';
import Galleria from 'primevue/galleria';
import Button from 'primevue/button';

import NavBar from '@/Components/NavBar.vue';
import Footer from '@/Components/Footer.vue';
// import Button from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue'
import TourContent from '@/Components/Tour/TourContent.vue';
import TourStepper from '@/Components/Tour/TourStepper.vue';
import TourIconBox from '@/Components/Tour/TourIconBox.vue';
import TourTextBox from '@/Components/Tour/TourTextBox.vue';

import { router, useForm, usePage } from '@inertiajs/vue3';
import { computed, defineProps, ref } from 'vue';
import { useFormatText } from '@/Composables/formatText';
import { useFormatPrice } from '@/Composables/formatPrice';
import customParseFormat from 'dayjs/plugin/customParseFormat';
import dayjs from 'dayjs';
dayjs.extend(customParseFormat);

const props = defineProps({
    tour: {
        type: Object,
        required: true,
    }
});

const form = useForm({
    tour_id: props.tour.id
});

const maximumMeter = ref(0);
const averageRating = ref(0.0);
const displayGalleria = ref(false);

const images = ref([
    {
        itemImageSrc: 'https://cdn.tourradar.com/s3/tour/1500x800/228465_624e71afe4389.jpg',
        thumbnailImageSrc: 'https://cdn.tourradar.com/s3/tour/1500x800/228465_624e71afe4389.jpg'
    },
    {
        itemImageSrc: 'https://primefaces.org/cdn/primevue/images/galleria/galleria1.jpg',
        thumbnailImageSrc: 'https://primefaces.org/cdn/primevue/images/galleria/galleria1.jpg'
    },
    {
        itemImageSrc: 'https://primefaces.org/cdn/primevue/images/galleria/galleria4.jpg',
        thumbnailImageSrc: 'https://primefaces.org/cdn/primevue/images/galleria/galleria4.jpg'
    },
    {
        itemImageSrc: 'https://primefaces.org/cdn/primevue/images/galleria/galleria6.jpg',
        thumbnailImageSrc: 'https://primefaces.org/cdn/primevue/images/galleria/galleria6.jpg'
    },
]);

const responsiveOptions = ref([
    {
        breakpoint: '991px',
        numVisible: 4
    },
    {
        breakpoint: '767px',
        numVisible: 3
    },
    {
        breakpoint: '575px',
        numVisible: 1
    }
]); 

const tourDetails = ref([
    {
        icon: 'pi pi-users',
        label: `Min. Group Size: ${props.tour.min_pax}`
    },
    {
        icon: 'pi pi-clock',
        label: `${dayjs(props.tour.start_time, 'HH:mm').format('HH:mm')} - ${dayjs(props.tour.end_time,'HH:mm').format('HH:mm')}`,
    },
    {
        icon: 'pi pi-flag',
        label: `Guide Type: ${useFormatText(props.tour.guide_type)}`
    },
    {
        icon: 'pi pi-car',
        label: `Travel Intensity: ${useFormatText(props.tour.travel_intensity)}`
    },
]);

const reserveTour = () => {
    try {
        form.get(route('tour.reserve'));
    } catch (error) {
        console.error('Error reserving tour:', error);
    }
};

const bookDate = (availabilityId) => {
    try {
        form.get(route('booking.show', { availabilityId: availabilityId }));
    } catch (error) {
        console.error('Error reserving tour:', error);
    }
};

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

const bookmark = computed(() => {
    const bookmarks = ref(props.tour.bookmarks);

    if (usePage().props.auth.user) {
        return bookmarks.value.find(bookmark => 
            parseInt(bookmark.user_id) === usePage().props.auth.user.id);
    } else {
        return bookmarks.value.find(bookmark => 
            bookmark.user_id == usePage().props.cookie.guest_id);
    }
});

const triggerBookmark = () => {
    const bookmarks = ref(props.tour.bookmarks);
    if (bookmarks.value.length > 0) {
        if (usePage().props.auth.user != null) {
            const bookmark = bookmarks.value.find(bookmark => 
                parseInt(bookmark.user_id) === usePage().props.auth.user.id);
            if (bookmark) {
                router.delete(route('bookmark.destroy', bookmark.id, { preserveScroll: true }));
                return;
            }
        } else {
            const bookmark = bookmarks.value.find(bookmark => 
                bookmark.user_id === usePage().props.cookie.guest_id);
            if (bookmark) {
                router.delete(route('bookmark.destroy', bookmark.id, { preserveScroll: true }));
                return;
            }
        }
    }
    router.post(route('bookmark.store', props.tour.id, { preserveScroll: true }), {
        onSuccess: () => emit('add-bookmark'),
        onError: (error) => console.log(error),
    });
};

const getMeterLabel = meterData => {
    const rating = ref(meterData);
    return rating.value[0].label;
};

const getMeterValue = meterData => {
    const rating = ref(meterData);
    return rating.value[0].value !== undefined ? rating.value[0].value : 0;
};

</script>

<template>
    <!-- Navbar -->
    <MarginLayout>
        <NavBar />
        <!-- <Breadcrumb  /> -->
        <div class="bg-white">
            <div class="mx-auto h-full lg:w-5/6 xl:max-w-7xl py-4">
                <div class="flex items-center justify-between">
                    <h1 class="text-3xl mb-3">{{ tour.name }}</h1>
                </div>
                <div class="flex flex-nowrap justify-center mb-4 w-full h-full gap-6">
                    <div class="flex-1 w-full h-full rounded-xl cursor-pointer" @click="displayGalleria = true">
                        <!-- Carousel -->
                        <Galleria :value="images" :responsiveOptions="responsiveOptions" :numVisible="5" 
                            :circular="true" :showThumbnails="false" :autoPlay="true" :transitionInterval="10000"
                        >
                            <template #item="slotProps">
                                <img :src="slotProps.item.itemImageSrc" :alt="slotProps.item.alt" class="w-full aspect-video object-cover rounded-xl" />
                            </template>
                            <template #thumbnail="slotProps">
                                <img :src="slotProps.item.thumbnailImageSrc" :alt="slotProps.item.alt" style="display: block;" />
                            </template>
                        </Galleria>

                        <Galleria v-model:visible="displayGalleria" :value="images" :responsiveOptions="responsiveOptions" :numVisible="4" containerStyle="max-width: 50%" :circular="true" :fullScreen="true" :showItemNavigators="true">
                            <template #item="slotProps">
                                <img :src="slotProps.item.itemImageSrc" :alt="slotProps.item.alt" class="w-full aspect-video object-cover rounded-xl" />
                            </template>
                            <template #thumbnail="slotProps">
                                <img :src="slotProps.item.thumbnailImageSrc" :alt="slotProps.item.alt" class="w-full aspect-video" />
                            </template>
                        </Galleria>
                    </div>

                    <!-- Buttons -->
                    <!-- <section class="flex flex-col gap-6"> -->
                    <section class="grid h-fit gap-6">
                        <div class="shadow-md outline outline-1 outline-primary-200 rounded-2xl p-4 h-fit">
                            <div class="flex flex-col justify-start w-full pb-3">
                                <span class="text-sm">From</span>
                                <strong class="text-xl">{{ useFormatPrice(tour.base_price) }}</strong>
                                <small>Price per day</small>
                                <small>{{ useFormatPrice(tour.base_price / tour.duration) }}</small>
                            </div>
                            <div class="flex flex-col gap-3 lg:min-w-[300px]">
                                <a v-if="tour.availabilities.length > 1" href="#available" class="flex-1">
                                    <Button label="Check Availability" class="w-full font-bold" rounded />
                                </a>
                                <Button v-else label="Reserve" @click="reserveTour" class="flex-1" rounded />
                                <Button label="Customize This Tour" class="flex-1" text outlined rounded />
                            </div>
                        </div>

                        <!-- Details -->
                        <div class="bg-primary-100/25 outline outline-2 outline-primary shadow-md rounded-2xl flex flex-col p-4 h-fit gap-2">
                            <div v-for="tour in tourDetails" class="flex items-center gap-3">
                                <i :class="tour.icon"></i>
                                <span>{{ tour.label }}</span>
                            </div>
                            <a v-if="tour.itinerary.days.length" href="#itinerary"><Button 
                                label="See itinerary details" icon="pi pi-map"
                                severity="constrast" class="w-full" rounded outlined
                            /></a>
                        </div>

                        <div class="flex gap-3 text-sm justify-between">
                            <div
                                class="flex items-center cursor-pointer hover:text-primary"
                                @click="displayGalleria = true"
                            >
                                <i class="pi pi-images"></i>
                                <span class="ml-2">View Gallery</span>
                            </div>
                            <div
                                class="flex items-center cursor-pointer hover:text-primary"
                                @click="triggerBookmark"
                            >
                                <i :class="!bookmark ? 'pi pi-bookmark' : 'pi pi-bookmark-fill'"></i>
                                <span class="ml-2">{{ !bookmark ? 'Save to bookmark' : 'Saved to bookmark' }}</span>
                            </div>
                        </div>

                        <!-- <Button label="Download Brochure" severity="constrast" rounded text outlined /> -->
                    </section>
                </div>
            </div>
        </div>

        <!-- Section -->
        <div class="mx-auto h-full lg:w-5/6 xl:max-w-7xl mb-6">
            <!-- Navigation Menu -->
            <!-- <div class="bg-white m-auto h-12 rounded-full shadow w-fit flex items-center justify-around gap-16 px-8 font-bold">
                <a href="#detail">Details</a>
                <a href="#itinerary">Itinerary</a>
                <a href="#available">Dates & Availability</a>
                <a href="#note">Notes</a>
                <a href="#review">Reviews</a>
            </div> -->

            <!-- Details -->
            <TourContent id="detail" title="Details">
                <div class="flex gap-4 mb-8">
                    <TourIconBox>
                        <template #header>
                            <i class="pi pi-map-marker"></i>
                            <!-- <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                                <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z" clip-rule="evenodd" />
                            </svg> -->
                        </template>
                        <template #main>
                            <h3 class="font-black">Pick-up Location</h3>
                            <span>{{ tour.pickup_location }}</span>
                        </template>
                    </TourIconBox>
                </div>
                <div>
                    <h2>Description</h2>
                    <p class="text-black text-justify">{{ tour.description }}</p>
                </div>  
                <TourTextBox label="Destinations" :value="tour.destinations" />
                <TourTextBox label="Activities" :value="tour.activities" />
                <div class="mt-8" v-if="tour.highlights.length != 0">
                    <h2>Highlights</h2>
                    <ul class="list-none leading-relaxed">
                        <li v-for="highlight in tour.highlights" class="flex gap-3 items-center">
                            <i class="pi pi-verified text-success"></i>
                            {{ highlight.description }}
                        </li>
                    </ul>
                </div>
            </TourContent>

            <!-- Itinerary -->
            <TourContent v-if="tour.itinerary.days.length != 0" id="itinerary" title="Itinerary">
                <TourStepper 
                    :days="tour.itinerary.days"
                />
            </TourContent>

            <!-- Customize -->
             <TourContent title="Customize Your Trip" titleCenter gradient>
                <div class="flex justify-between">
                    <!-- <Button label="Customize" severity="plain" outlined rounded /> -->
                </div>
             </TourContent>

            <!-- Availability -->
            <TourContent id="available" title="Dates & Availability">
                <DataTable v-if="tour.availabilities.length != 0" :value="tour.availabilities" stripedRows tableStyle="max-width: 100%">
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
                                @click="bookDate(data.pivot.availability_id)"
                                text rounded outlined
                            />
                        </template>
                    </Column>
                </DataTable>
                <div v-else>
                    <p>No available dates currently. Click <a href="#reserve" class="underline">'Reserve'</a> to reserve a slot for this tour.</p>
                </div>
            </TourContent>

            <!-- Notes -->
            <TourContent id="notes" title="Notes">
                <Accordion :multiple="true">
                    <AccordionTab v-for="subject in tour.note.subjects" :header="subject.name">
                        <ul v-for="bulletPoint in subject.bullet_points">
                            <li>{{ bulletPoint.content }}</li>
                        </ul>
                    </AccordionTab>
                </Accordion>
            </TourContent>

            <!-- Review -->
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
        <Footer />
    </MarginLayout>
</template>