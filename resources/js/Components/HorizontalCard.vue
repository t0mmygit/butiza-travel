<script setup>
import { useFormatPrice } from '@/Composables/formatPrice.js';
import { useFormatText } from '@/Composables/formatText';

import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

import Tag from 'primevue/tag';

import { router, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { TOAST_ERROR, TOAST_SECONDARY, TOAST_SUCCESS } from '@/constant';

const emit = defineEmits(['compare-tour', 'host-tour', 'add-bookmark', 'view-tour-failed'])

const props = defineProps({
    tour: {
        type: Object
    },
    items: {
        type: Array
    },
    mode: {
        type: Boolean
    }
});


const viewTour = () => {
    router.get(route('tour.show', { tour: props.tour.slug }), {
        onError: (error) => emit('view-tour-failed', viewTourFailedMessage),
    });
};

function customMessage(severity, summary, detail, life = 6000) {
    return {
        severity,
        summary,
        detail,
        life,
    };
}

const viewTourFailedMessage = () => customMessage(TOAST_ERROR, 'Something went wrong!', `Tour could not be found.`);
const addBookmarkMessage = () => customMessage('success', 'Bookmark Added', 'You have successfully added a bookmark.');

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
        onSuccess: () => emit('add-bookmark', addBookmarkMessage),
        onError: (error) => console.log(error),
    });
};

const getLowestPackagePrice = computed(() => {
    var lowestPrice = parseInt(props.tour.packages[0].price);
    props.tour.packages.forEach(item => {
        var itemPrice = parseInt(item.price);
        
        if (lowestPrice > itemPrice) {
            lowestPrice = itemPrice;
        }
    });

    return lowestPrice;
});;

const itemsListener = computed(() => {
    let index = props.items.findIndex(item => item.id === props.tour.id);

    return index != -1 ?  true : false;
});

const rating = computed(() => {
    var reviewRatings = 0;

    props.tour.reviews.forEach(review => {
        reviewRatings += review.rating;
    });

    return parseFloat(reviewRatings / props.tour.reviews.length).toFixed(1);
});

const calculatePricePerDay = computed(() => getLowestPackagePrice.value / props.tour.duration);

const calculatePriceAfterDiscount = computed(() => {
    if (! hasDiscount.value) {
        return;
    }

    var lowestPrice = getLowestPackagePrice.value;
    var discount = props.tour.discount.percentage;

    return lowestPrice - (lowestPrice * (discount / 100));
});

const calculatePricePerDayAfterDiscount = computed(() => calculatePriceAfterDiscount.value / props.tour.duration) 

const displayLowestPackagePrice = computed(() => useFormatPrice(getLowestPackagePrice.value, 2));

const displayPricePerDay = computed(() => useFormatPrice(calculatePricePerDay.value));
const displayDiscount = computed(() => props.tour.discount?.percentage + '% Discount');
const displayDiscountType = computed(() => props.tour.discount?.type);
const displayPriceAfterDiscount = computed(() => useFormatPrice(calculatePriceAfterDiscount.value, 2, false));
const displayPricePerDayAfterDiscount = computed(() => useFormatPrice(calculatePricePerDayAfterDiscount.value, 2, false));

const hasDiscount = computed(() => props.tour.discount != null);

const tags = computed(() => hasDiscount.value ? [
    {
        'value': displayDiscountType.value,
        'severity': TOAST_SECONDARY,
    },
    {
        'value': displayDiscount.value,
        'severity': TOAST_SUCCESS
    },
] : []);

const formatDestination = destinations => destinations.map(destination => destination.name).join(', ');

</script>

<template>
    <div v-if="tour" class="mb-4">
        <div class="bg-white rounded-lg shadow flex p-3 justify-between gap-4 items-start max-w-full">

            <!-- Card Image -->
            <div id="image" class="aspect-square bg-slate-300 flex-initial min-w-60 max-w-60 relative">
                <img 
                    class="object-cover rounded size-60" 
                    src="https://cdn.tourradar.com/s3/tour/1500x800/228465_624e71afe4389.jpg"
                >
                <span class="group absolute top-0 right-0 p-3 cursor-pointer">
                    <div class="group-hover:bg-white bg-black/50 rounded-full size-8 flex justify-center items-center" @click="triggerBookmark">
                        <i v-if="!bookmark" class="group-hover:text-black pi pi-bookmark text-white"></i>
                        <i v-else class="group-hover:text-black pi pi-bookmark-fill text-yellow-300"></i>
                    </div>
                </span>
            </div>

            <!-- Card Details -->
            <div class="mr-auto w-full">
                <div class="flex gap-2">
                    <Tag v-for="tag in tags" :value="tag.value" :severity="tag.severity" />
                </div>
                <h1 class="line-clamp-2">{{ tour.name }}</h1>
                <div class="font-medium text-gray-500 mb-4">
                    <div v-if="tour.reviews.length != 0" class="flex items-center gap-1">
                        <span class="text-yellow-500">★ {{ rating }}</span>
                        <span class="text-neutral-500">({{ tour.reviews.length > 1 ? `${tour.reviews.length} reviews` : `${tour.reviews.length} review`}})</span>
                    </div>
                    <span v-else>No reviews yet</span>
                </div>

                <dl class="grid grid-cols-3 mb-4">
                    <dt class="col-span-1"><strong>Destinations</strong></dt>
                    <dd class="line-clamp-1 col-span-2">{{ formatDestination(tour.destinations) }}</dd>
                    <dt><strong>Age Range</strong></dt>
                    <dd>up to {{ tour.max_age }} years olds</dd>
                </dl>

                <div id="additional" class="flex justify-between pr-4">
                    <div>
                        <h3 class="font-bold">Guide Type</h3>
                        <span>{{ useFormatText(tour.guide_type) }}</span>
                    </div>
                    <div>
                        <h3 class="font-bold">Travel Intensity</h3>
                        <span>{{ useFormatText(tour.travel_intensity) }}</span>
                    </div>
                    <div>
                        <h3 class="font-bold">Durations</h3>
                        <span>{{ tour.duration > 1 ? `${tour.duration} Days` : `${tour.duration} Day` }}</span>
                    </div>
                </div>
            </div>

            <!-- Card Price -->
            <div class="grid grid-rows-2 min-w-max">
                <div class="flex flex-col">
                    <div v-if="! hasDiscount" class="flex flex-col justify-start w-full mb-3">
                        <span class="text-sm">From</span>
                        <strong class="text-xl">{{ displayLowestPackagePrice }}</strong>
                        <small>Price per day</small>
                        <small>{{ displayPricePerDay }}</small>
                    </div>
                    <div v-else class="flex flex-col mb-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm">From <span class="line-through">{{ displayLowestPackagePrice }}</span></span>
                        </div>
                        <span class="font-bold text-xl">{{ displayPriceAfterDiscount }}</span>
                        <small>Price per day</small>
                        <small>{{ displayPricePerDayAfterDiscount }}</small>
                    </div>
                </div>
                <div class="flex flex-col place-self-end gap-2">
                    <PrimaryButton @click="viewTour">
                        View Tour
                    </PrimaryButton>
                    <SecondaryButton v-if="!mode" @click="$emit('compare-tour', tour)" :active="itemsListener">
                        <span>Compare</span>
                    </SecondaryButton>
                    <SecondaryButton v-else @click="$emit('host-tour', tour)">
                        <span>Host Tour</span>
                    </SecondaryButton>
                </div>
            </div>

        </div>
    </div>

    <!-- Empty Tours -->
    <div v-else>
        Nothing to see here...
    </div>
</template>