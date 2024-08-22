<script setup>
import Galleria from 'primevue/galleria';
import Button from 'primevue/button';
import Tag from 'primevue/tag';

import { computed, ref } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import { useFormatText } from '@/Composables/formatText';
import { useFormatPrice } from '@/Composables/formatPrice';

import customParseFormat from 'dayjs/plugin/customParseFormat';
import dayjs from 'dayjs';
dayjs.extend(customParseFormat);

const emit = defineEmits(['reserve-tour']);

const props = defineProps({
    tour: {
        type: Object,
        required: true,
    }
});

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

const getLowestPackagePrice = computed(() => {
    var lowestPrice = parseInt(props.tour.packages[0].price);
    props.tour.packages.forEach(item => {
        var itemPrice = parseInt(item.price);
        
        if (lowestPrice > itemPrice) {
            lowestPrice = itemPrice;
        }
    });

    return lowestPrice;
});

const calculatePricePerDay = computed(() => getLowestPackagePrice.value / props.tour.duration);

const calculatePriceAfterDiscount = computed(() => {
    if (! hasDiscount.value) {
        return;
    }

    var lowestPrice = getLowestPackagePrice.value;
    var discount = props.tour.discount.percentage;

    return lowestPrice - (lowestPrice * (discount / 100));
})

const calculatePricePerDayAfterDiscount = computed(() => calculatePriceAfterDiscount.value / props.tour.duration) 

const displayDiscount = computed(() => props.tour.discount.percentage + '% DISCOUNT');
const displayLowestPackagePrice = computed(() => useFormatPrice(getLowestPackagePrice.value));
const displayPricePerDay = computed(() => useFormatPrice(calculatePricePerDay.value));
const displayPriceAfterDiscount = computed(() => useFormatPrice(calculatePriceAfterDiscount.value));
const displayPricePerDayAfterDiscount = computed(() => useFormatPrice(calculatePricePerDayAfterDiscount.value));

const hasDiscount = computed(() => props.tour.discount != null);

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

</script>

<template>

    <div class="bg-white">
        <div class="mx-auto h-full lg:w-5/6 xl:max-w-7xl py-4">
            <div class="flex items-center justify-between">
                <h1 class="text-3xl mb-3">{{ tour.name }}</h1>
            </div>
            <div class="flex flex-nowrap justify-center mb-4 w-full h-full gap-6">
                <!-- Carousel -->
                <div class="flex-1 w-full h-full rounded-xl cursor-pointer" @click="displayGalleria = true">
                    <Galleria
                        :value="images"
                        :responsiveOptions="responsiveOptions"
                        :numVisible="5"
                        :circular="true"
                        :showThumbnails="false"
                        :autoPlay="true"
                        :transitionInterval="10000"
                    >
                        <template #item="slotProps">
                            <img :src="slotProps.item.itemImageSrc" :alt="slotProps.item.alt" class="w-full aspect-video object-cover rounded-xl" />
                        </template>
                        <template #thumbnail="slotProps">
                            <img :src="slotProps.item.thumbnailImageSrc" :alt="slotProps.item.alt" style="display: block;" />
                        </template>
                    </Galleria>
                    <Galleria
                        v-model:visible="displayGalleria"
                        :value="images"
                        :responsiveOptions="responsiveOptions"
                        :numVisible="4"
                        containerStyle="max-width: 50%"
                        :circular="true"
                        :fullScreen="true"
                        :showItemNavigators="true"
                    >
                        <template #item="slotProps">
                            <img :src="slotProps.item.itemImageSrc" :alt="slotProps.item.alt" class="w-full aspect-video object-cover rounded-xl" />
                        </template>
                        <template #thumbnail="slotProps">
                            <img :src="slotProps.item.thumbnailImageSrc" :alt="slotProps.item.alt" class="w-full aspect-video" />
                        </template>
                    </Galleria>
                </div>
                <!-- Price / Details Section -->
                <section class="grid h-fit gap-6">
                    <div class="shadow-md outline outline-1 outline-primary-200 rounded-2xl p-4 h-fit">
                        <div v-if="! hasDiscount"  class="flex flex-col justify-start w-full mb-3">
                            <span class="text-sm">From</span>
                            <strong class="text-xl">{{ displayLowestPackagePrice }}</strong>
                            <small>Price per day</small>
                            <small>{{ displayPricePerDay }}</small>
                        </div>
                        <div v-else class="flex flex-col mb-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm line-through">From {{ displayLowestPackagePrice }}</span>
                                <Tag :value="displayDiscount" severity="success" />
                            </div>
                            <span class="font-bold text-xl">From {{ displayPriceAfterDiscount }}</span>
                            <small>Price per day</small>
                            <small>{{ displayPricePerDayAfterDiscount }}</small>
                        </div>
                        <div class="flex flex-col gap-3 lg:min-w-[300px]">
                            <a v-if="tour.availabilities.length > 1" href="#available" class="flex-1">
                                <Button label="Check Availability" class="w-full font-bold" rounded />
                            </a>
                            <Button v-else label="Reserve" @click="emit('reserve-tour')" class="flex-1" rounded />
                            <!-- <Button disabled label="Customize This Tour" class="flex-1" text outlined rounded /> -->
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
</template>