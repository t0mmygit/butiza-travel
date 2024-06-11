<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'
import PrimeButton from 'primevue/button';
import { router, usePage, Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { useFormatPrice } from '@/Composables/formatPrice.js';

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

// console.log(usePage().props.cookie.guest_id);

const emit = defineEmits(['compare-tour', 'host-tour', 'add-bookmark'])

const viewTour = () => {
    try {
        router.get(route('tour.show', {
            id: props.tour.id
        }));
    } catch (error) {
        console.error('Error viewing tours:', error);
    }
};

const bookmark = computed(() => {
    const bookmarks = ref(props.tour.bookmarks);
    // const userBookmarks = ref(props.tour.userBookmarks);
    // const guestBookmarks = ref(props.tour.guestBookmarks);

    // if (usePage().props.auth.user) {
    //     return userBookmarks.value.find(bookmark => 
    //         bookmark.user_id === usePage().props.auth.user.id);
    // } else {
    //     return guestBookmarks.value.find(bookmark => 
    //         bookmark.user_id === usePage().props.cookie.guest_id);
    // }

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
    // const userBookmarks = ref(props.tour.userBookmarks);
    // const guestBookmarks = ref(props.tour.guestBookmarks);
    
    // if (userBookmarks.value.length > 0 && usePage().props.auth.user) {
    //     const bookmark = userBookmarks.value.find(bookmark => 
    //         bookmark.user_id === usePage().props.auth.user.id);
    //     if (bookmark) {
    //         router.delete(route('bookmark.destroy', bookmark.id));
    //         return;
    //     }
    // } else {
    //     const bookmark = guestBookmarks.value.find(bookmark => 
    //         bookmark.user_id === usePage().props.auth.user.id);
    //     if (bookmark) {
    //         router.delete(route('bookmark.destroy', bookmark.id));
    //         return;
    //     }
    // }
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

const formatPricePerDay = (price) => {
    price = Math.ceil(price / props.tour.day);
    return useFormatPrice(price);
};

const itemsListener = computed(() => {
    let index = props.items.findIndex(item => item.id === props.tour.id);
    return index != -1 ?  true : false;
});

const formatDestination = destinations => destinations.map(destination => destination.name).join(', ');

</script>

<template>
    <div v-if="tour" class="mb-4">
        <div class="bg-white rounded-lg shadow flex p-3 justify-between gap-4 items-start max-w-full">

            <!-- Card Image -->
            <div id="image" class="aspect-square bg-slate-300 flex-initial min-w-60 max-w-60 relative">
                <img 
                    class="object-cover rounded size-60" 
                    src="https://static.travelstride.com/store/c3/bbdab0beb54663a00825b0096702d3/e89d69f64504697816cc35e6eb3ba94d.jpg"
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
                <h1 class="mb-2 line-clamp-2">{{ tour.name }}</h1>
                <div class="font-medium text-gray-500 mb-4">
                    <span>No reviews yet</span>
                </div>

                <dl class="grid grid-cols-3 mb-4">
                    <dt class="col-span-1"><strong>Destinations</strong></dt>
                    <dd class="line-clamp-1 col-span-2">{{ formatDestination(tour.destinations) }}</dd>
                    <dt><strong>Age Range</strong></dt>
                    <dd>up to {{ tour.max_age }} years olds</dd>
                </dl>

                <div id="additional" class="flex gap-10 pr-4">
                    <div>
                        <h3 class="font-bold">Travel Intensity</h3>
                        <span>{{ tour.travel_intensity }}</span>
                    </div>
                    <div>
                        <h3 class="font-bold">Lodging Level</h3>
                        <span>Excellent</span>
                    </div>
                    <div>
                        <h3 class="font-bold">Durations</h3>
                        <span v-if="tour.day > 1">{{ tour.day }} Days</span>
                        <span v-else-if="tour.day = 1">{{ tour.day }} Day</span>
                    </div>
                </div>
            </div>

            <!-- Card Price -->
            <div class="grid grid-rows-2 min-w-max">
                <div class="flex flex-col">
                    <span>From</span>
                    <strong class="text-2xl">{{ useFormatPrice(tour.base_price) }}</strong>
                    <span>Price per day</span>
                    <strong>{{ formatPricePerDay(tour.base_price) }}</strong>
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