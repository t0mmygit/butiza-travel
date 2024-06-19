<script setup>
import NavLink from '@/Components/NavLink.vue';
import RoundedButton from '@/Components/RoundedButton.vue';
import ContactForm from '@/Components/Dialog/ContactForm.vue';

import SplitButton from 'primevue/splitbutton';
import Dialog from 'primevue/dialog';
import IconField from 'primevue/iconfield';
import InputIcon from 'primevue/inputicon';
import InputText from 'primevue/inputtext';
import Toast from 'primevue/toast';
import { useToast } from 'primevue/usetoast';

import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

const mask = ref(false);
const modal = ref(false);
const toast = useToast();
const contactDialog = ref(false);

const items = [
    {
        label: 'User Profile',
        command: () => router.get(route('profile.account'))
    },
    {
        label: 'Log Out',
        command: () => router.delete(route('auth.destroy')),
    }
];

const closeContactDialog = () => {
    contactDialog.value = false;
    toast.add({ severity: 'success', summary: 'Message Successfully Submitted', detail: 'Thank you for reaching out! We will get back to you as soon as possible.', life: 3000 });
};

</script>

<template>
    <Toast />
    <ModalAuthenticate :show="modal" @reset="modal = false" />
    <Dialog v-model:visible="contactDialog" modal header="Ask a Question" >
        <ContactForm @close-contact-dialog="closeContactDialog" />
    </Dialog>

    <div class="bg-white h-20 shadow z-50">
        <div class="flex items-center h-full m-auto lg:w-5/6 xl:w-11/12 2xl:w-7/12">
            <div id="left" class="mr-auto">
                <SvgLogo />
            </div>
            <IconField v-if="!route().current('home')" iconPosition="left">
                <InputIcon class="pi pi-search" />
                <InputText 
                    placeholder="Search" 
                    class="rounded-lg" 
                    @focusin="mask = true" @focusout="mask = false"
                />
            </IconField>
            <div class="flex gap-4 mx-auto">
                <NavLink label="Home" :href="route('home')" :active="route().current('home')" />
                <NavLink label="Explore" :href="route('explore.index')" :active="route().current('explore.index')" />
                <NavLink label="Community" :href="route('community')" :active="route().current('community')" />
            </div>
            <div class="flex gap-4">
                <RoundedButton class="flex gap-2" @click="contactDialog = true">
                    <i class="pi pi-info-circle"></i>Contact Us
                </RoundedButton>
                <RoundedButton class="flex gap-2" @click="route('profile.bookmark')">
                    <i class="pi pi-bookmark"></i>Bookmark
                </RoundedButton>
                <RoundedButton v-if="!$page.props.auth.user" class="flex gap-2" @click="modal = true">
                    <i class="pi pi-sign-in"></i>Sign In
                </RoundedButton>
                <SplitButton v-else 
                    :model="items" 
                    :label="$page.props.auth.user.name" 
                    severity="secondary" rounded 
                    @click="$inertia.get(route('profile.account'))" 
                />
            </div>
        </div>
    </div>
    <div v-if="mask" class="absolute bg-black/50 h-screen w-full z-40"></div>
</template>