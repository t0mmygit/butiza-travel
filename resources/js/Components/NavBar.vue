
<script setup>
import ContactForm from '@/Components/Dialog/ContactForm.vue';

import SplitButton from 'primevue/splitbutton';
import Dialog from 'primevue/dialog';
import Button from 'primevue/button';
import Toast from 'primevue/toast';
import { useToast } from 'primevue/usetoast';

import { ref } from 'vue';
import { router, usePage } from '@inertiajs/vue3';

const mask = ref(false);
const modal = ref(false);
const toast = useToast();
const contactDialog = ref(false);

defineProps({
    partner: {
        type: Boolean,
        default: false,
    }
});

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

const directToUserAccount = () => {
    if (usePage().props.auth.user.role === 'customer') {
        router.get(route('profile.account'));
    }

    if (usePage().props.auth.user.role === 'partner') {
        router.get(route('partner-account'));
    }
}

</script>

<template>
    <Toast />
    <ModalAuthenticate :show="modal" @reset="modal = false" />
    <Dialog v-model:visible="contactDialog" modal header="Ask a Question" >
        <ContactForm @close-contact-dialog="closeContactDialog" />
    </Dialog>

    <div class="bg-white h-20 shadow z-50 sticky top-0">
        <div class="flex items-center mx-auto h-full lg:w-5/6 xl:max-w-7xl">
            <div id="left" class="mr-auto">
                <ApplicationLogo />
            </div>
            <div v-if="partner">
            </div>
            <div v-else class="flex gap-3">
                <Button 
                    label="Explore" 
                    plain text rounded
                    class="text-sm"
                    @click="$inertia.get(route('explore.index'))"
                />
                <Button 
                    label="Community" 
                    plain text rounded 
                    class="text-sm"
                    @click="$inertia.get(route('community'))"
                />
                <Button 
                    label="Promotion" 
                    plain text rounded 
                    class="text-sm"
                    @click="contactDialog = true"
                />
                <Button 
                    label="Contact Us" 
                    plain text rounded 
                    class="text-sm"
                    @click="contactDialog = true"
                />
                <Button 
                    icon="pi pi-bookmark"
                    plain text rounded 
                    class="text-sm"
                    @click="$inertia.get(route('profile.bookmark'))"
                />
                <Button v-if="!$page.props.auth.user"
                    label="Sign in"
                    plain rounded 
                    class="text-sm"
                    @click="modal = true"
                />
                <SplitButton v-else 
                    :model="items" 
                    :label="$page.props.auth.user.first_name" 
                    severity="secondary" rounded 
                    @click="directToUserAccount" 
                />
            </div>
        </div>
    </div>
    <div v-if="mask" class="absolute bg-black/50 h-screen w-full z-40"></div>
</template>