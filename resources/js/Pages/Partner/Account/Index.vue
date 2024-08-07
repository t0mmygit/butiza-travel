<script setup>
import NavBar from '@/Components/NavBar.vue';
import Inbox from '@/Pages/Partner/Account/Inbox.vue';
import BookingForm from '@/Pages/Partner/Account/BookingForm.vue';
import PersonalForm from '@/Pages/Partner/Account/PersonalForm.vue';
import CompanyForm from '@/Pages/Partner/Account/CompanyForm.vue';
import PreferenceForm from '@/Pages/Partner/Account/PreferenceForm.vue';
import BookingHistory from '@/Pages/Partner/Account/BookingHistory.vue';
import Toast from 'primevue/toast';
import { useToast } from 'primevue/usetoast';

import Menu from 'primevue/menu';
import InlineMessage from 'primevue/inlinemessage';

import { onMounted, ref } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    user: {
        type: Object,
        required: true,
    },
    notifications: {
        type: Object,
    },
    tours: {
        type: Object,
    },
    contactMethods: {
        tyoe: Object,
        required: true,
    },
    flash: {
        type: Object,
    }
});

const toast = useToast();
const sectionIndex = ref('Inbox');

onMounted(() => {
    if (props.flash?.status != null || props.flash?.message != null) {
        toast.add({ 
            severity: props.flash.status, 
            summary: props.flash.status, 
            detail: props.flash.message, 
            life: 3000 
        });
    }
});

const items = ref([
    {
        label: 'Inbox', 
        icon: 'pi pi-inbox', 
        command: (event) => { sectionIndex.value = event.item.label; },
    },
    {
        label: 'Tour',
        items: [
            {
                label: 'Booking',
                icon: 'pi pi-book',
                command: (event) => { sectionIndex.value = event.item.label; },
            },
            {
                label: 'History',
                icon: 'pi pi-history',
                command: (event) => { sectionIndex.value = event.item.label; },
            }
        ],
    },
    {
        label: 'Settings',
        items: [
            {
                label: 'Account',
                icon: 'pi pi-user', 
                command: (event) => { sectionIndex.value = event.item.label; },
            },
        ],
    },
    {
        label: 'Preference',
        icon: 'pi pi-cog',
        command: (event) => { sectionIndex.value = event.item.label; },
    },
    {
        label: 'Logout',
        icon: 'pi pi-sign-out',
        command: () => { router.post(route('logout')); },
    }
]);


const isRequiredCredentialsFilled = () => {
    return props.user.partner.registration_number;
    // return (props.user.partner.registration_number && props.user.partner.ssm_path);
};

const isVerificationStatusPending = () => props.user.partner.verification_status === 'pending';
const isVerificationStatusApproved = () => props.user.partner.verification_status === 'approved';
const isVerificationRejected = () => props.user.partner.verification_status === 'rejected';

</script>

<template>
    <Toast class="z-50" />
    <NavBar partner />
    
    <div class="min-h-full mb-3">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <div class="mb-4">
                <InlineMessage v-if="isVerificationStatusPending() && !isRequiredCredentialsFilled()" severity="warn" class="flex justify-start min-w-full">
                    Your partner account is unverified. Please update your 
                    <a @click="sectionIndex = 'Account'" class="underline">Registration Number</a> 
                    to start the verification process.
                </InlineMessage>
                <InlineMessage v-if="isVerificationStatusPending() && isRequiredCredentialsFilled()" severity="info" class="flex justify-start min-w-full">
                    Please wait for your partner account to be verified.
                </InlineMessage>
                <InlineMessage v-if="isVerificationRejected()" severity="error" class="flex justify-start min-w-full">
                    Your partner account has been rejected. Please read the rejection reason in your inbox or email.
                </InlineMessage>
            </div>
            <div class="flex gap-6">
                <aside>
                    <div 
                        v-if="isVerificationStatusApproved() && user.partner.discount_id != null"
                        class="bg-primary-100 mb-4 outline outline-1 outline-primary rounded-md" 
                    >
                        <div class="flex flex-col px-3 py-4">
                            <h3>{{ user.partner.discount.type }}</h3>
                            <span>Enjoy {{ user.partner.discount.percentage }}% off</span>
                        </div>
                    </div>
                    <Menu 
                        :model="items"
                        class="sticky top-24"
                    />
                </aside>
                <main class="w-full flex flex-col gap-4">
                    <Inbox
                        v-if="sectionIndex === 'Inbox'"
                        :notifications="notifications"
                    />
                    <div v-if="sectionIndex === 'Booking'">
                        <BookingForm
                            v-if="isVerificationStatusApproved()"
                            :tours="tours"
                            :partner="user.partner"
                        />
                        <div v-else class="bg-error-100 shadow rounded sm:rounded-md outline outline-1 outline-error">
                            <div class="flex justify-center items-center gap-3 p-4">
                                <i class="pi pi-lock"></i>
                                <span>Your account is not verified yet.</span>
                            </div>
                        </div>
                    </div>
                    <div v-if="sectionIndex === 'History'">
                        <BookingHistory
                            v-if="isVerificationStatusApproved()"
                            :bookings="user.bookings"
                        />
                        <div v-else class="bg-error-100 shadow rounded sm:rounded-md outline outline-1 outline-error">
                            <div class="flex justify-center items-center gap-3 p-4">
                                <i class="pi pi-lock"></i>
                                <span>Your account is not verified yet.</span>
                            </div>
                        </div>
                    </div>
                    <div v-if="sectionIndex === 'Account'" class="flex flex-col gap-4">
                        <PersonalForm
                            :user="user"
                        />
                        <CompanyForm 
                            :user="user"
                        />
                    </div>
                    <div v-if="sectionIndex === 'Preference'">
                        <PreferenceForm
                            :partner="user.partner"
                            :contact_methods="contactMethods"
                        />
                    </div>
                </main>
            </div>
        </div>
    </div>
</template>