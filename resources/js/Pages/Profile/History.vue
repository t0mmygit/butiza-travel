<script setup>
import ProfileLayout from '@/Layouts/ProfileLayout.vue';
import BookingTable from '@/Pages/Profile/HistoryPartials/BookingTable.vue';
import ReservationTable from '@/Pages/Profile/HistoryPartials/ReservationTable.vue';
import PaymentTable from '@/Pages/Profile/HistoryPartials/PaymentTable.vue';

import Skeleton from 'primevue/skeleton';
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import Toast from 'primevue/toast';
import { useToast } from 'primevue/usetoast';

import { onMounted, ref } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    model: {
        type: String,
        required: true,
    },
    history: {
        type: Object,
    },
    flash: {
        type: Object,
    }
});

const activeTab = ref(null);
const onReload = ref(false);
const toast = useToast();

function handleFilterHistory() {
    let modelType = convertToModelType();

    router.visit(route('profile.history', { model: modelType }), {
        method: 'get',
        onStart: () => {
            onReload.value = true;
        },
    });
}

function convertToModelType() {
    switch (activeTab.value) {
        case 0:
            return 'booking';
        case 1:
            return 'reservation';
        case 2:
            return 'payment';
        default:
            return 'booking';
    }
}

function convertModelTypeToIndex() {
    switch (props.model) {
        case 'booking':
            return 0;
        case 'reservation':
            return 1;
        case 'payment':
            return 2;
        default:
            return 0;
    }
}

function handleToast(toastMessage) {
    toast.add(toastMessage);
}

onMounted(() => {
    activeTab.value = convertModelTypeToIndex();

    if (props.flash.status && props.flash.message) {
        toast.add({
            severity: props.flash.status,
            summary: props.flash.status,
            detail: props.flash.message,
            life: 3000,
        });
    }
});

</script>

<template>
    <ProfileLayout section="History">
        <Toast class="z-50" />
        <div class="my-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <Toast class="z-50" />
                <h1 class="text-2xl">History</h1>
                <main class="border border-surfaceBorder rounded sm:rounded-lg">
                    <TabView 
                        v-model:activeIndex="activeTab"
                        @tab-change="handleFilterHistory()"
                    >
                        <div>
                            <TabPanel header="Booking">

                                <BookingTable
                                    v-if="!onReload"
                                    :bookings="history"
                                    @cancel-booking="handleToast"
                                />
                                <Skeleton v-else width="100%" height="4rem" />

                            </TabPanel>
                            <TabPanel header="Reservation">

                                <ReservationTable
                                    v-if="!onReload"
                                    :reservations="history"
                                />
                                <Skeleton v-else width="100%" height="4rem" />
                                
                            </TabPanel>
                            <TabPanel header="Payment">

                                <PaymentTable
                                    v-if="!onReload"
                                    :payments="history"
                                />
                                <Skeleton v-else width="100%" height="4rem" />

                            </TabPanel>
                        </div>
                    </TabView>
                </main>

            </div>
        </div>
    </ProfileLayout>
</template>