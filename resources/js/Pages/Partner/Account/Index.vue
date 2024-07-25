<script setup>
import NavBar from '@/Components/NavBar.vue';
import Inbox from '@/Pages/Partner/Account/Inbox.vue';
import PersonalForm from '@/Pages/Partner/Account/PersonalForm.vue';
import CompanyForm from '@/Pages/Partner/Account/CompanyForm.vue';

import Menu from 'primevue/menu';
import InlineMessage from 'primevue/inlinemessage';

import { ref } from 'vue';

const props = defineProps({
    user: {
        type: [Array, Object],
        required: true,
    },
    notifications: {
        type: Object,
    },
});

const sectionIndex = ref(0);

const items = ref([
    {
        label: 'Inbox', 
        icon: 'pi pi-inbox', 
        command: () => { sectionIndex.value = 0; },
    },
    {
        label: 'Settings', 
        items: [
            {
                label: 'Account Information',
                icon: 'pi pi-user', 
                command: () => { sectionIndex.value = 1; },
            },
        ],
    }
]);

const hasRequiredCredentials = () => {
    return props.user.partner.registration_number != null;
    // return (props.user.partner.registration_number && props.user.partner.ssm_path);
};

</script>

<template>
    <NavBar partner />

    <div class="min-h-full">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <div class="mb-4">
                <InlineMessage v-if="!user.partner.verification_status && !hasRequiredCredentials" class="flex justify-start min-w-full">
                    Your partner account is unverified. Please update your 
                    <a @click="sectionIndex = 1" class="underline">Registration Number</a> and <a @click="sectionIndex = 1" class="underline">SSM</a> 
                    to start the verification process.
                </InlineMessage>
                <InlineMessage v-if="!user.partner.verification_status && hasRequiredCredentials" severity="info" class="flex justify-start min-w-full">
                    Please wait for your partner account to be verified.
                </InlineMessage>
            </div>
            <div class="flex gap-6">
                <aside>
                    <Menu :model="items" />
                </aside>
                <main class="w-full flex flex-col gap-4">
                    <Inbox
                        v-if="sectionIndex === 0"
                        :notifications="notifications"
                    />
                    <div v-if="sectionIndex === 1" class="flex flex-col gap-4">
                        <PersonalForm
                            :user="user"
                        />
                        <CompanyForm 
                            :user="user"
                        />
                    </div>
                </main>
            </div>
        </div>
    </div>
</template>