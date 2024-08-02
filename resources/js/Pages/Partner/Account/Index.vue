<script setup>
import NavBar from '@/Components/NavBar.vue';
import Inbox from '@/Pages/Partner/Account/Inbox.vue';
import BookingForm from '@/Pages/Partner/Account/BookingForm.vue';
import PersonalForm from '@/Pages/Partner/Account/PersonalForm.vue';
import CompanyForm from '@/Pages/Partner/Account/CompanyForm.vue';
import PreferenceForm from '@/Pages/Partner/Account/PreferenceForm.vue';
import BookingHistory from '@/Pages/Partner/Account/BookingHistory.vue';

import Menu from 'primevue/menu';
import InlineMessage from 'primevue/inlinemessage';

import { ref } from 'vue';
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
    }
});

const sectionIndex = ref('Inbox');

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
    <NavBar partner />

    <div class="min-h-full">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <div class="mb-4">
                <InlineMessage v-if="isVerificationStatusPending() && !isRequiredCredentialsFilled()" severity="warn" class="flex justify-start min-w-full">
                    Your partner account is unverified. Please update your 
                    <a @click="sectionIndex = 1" class="underline">Registration Number</a> and <a @click="sectionIndex = 1" class="underline">SSM</a> 
                    to start the verification process.
                </InlineMessage>
                <InlineMessage v-if="isVerificationStatusPending() && isRequiredCredentialsFilled()" severity="info" class="flex justify-start min-w-full">
                    Please wait for your partner account to be verified.
                </InlineMessage>
                <InlineMessage v-if="isVerificationRejected()" severity="danger" class="flex justify-start min-w-full">
                    Your partner account has been rejected. Please read the rejection reason in your inbox or email.
                </InlineMessage>
            </div>
            <div class="flex gap-6">
                <aside>
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