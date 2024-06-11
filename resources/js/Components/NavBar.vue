<script setup>
import NavLink from '@/Components/NavLink.vue';
import RoundedButton from '@/Components/RoundedButton.vue';
import ContactForm from '@/Components/Dialog/ContactForm.vue';

import SplitButton from 'primevue/splitbutton';
import Button from 'primevue/button';
import Dialog from 'primevue/dialog';
import IconField from 'primevue/iconfield';
import InputIcon from 'primevue/inputicon';
import InputText from 'primevue/inputtext';

import { ref } from 'vue';
import { router, usePage } from '@inertiajs/vue3';

const modal = ref(false);
const contactDialog = ref(false);
const mask = ref(false);

const items = [
    {
        label: 'User Profile',
        command: () => router.get(route('profile.index'))
    },
    {
        label: 'Log Out',
        command: () => router.delete(route('auth.destroy')),
    }
]

const directToBookmark = () => {
    router.get(route('profile.bookmark'));
}

</script>

<template>
    <ModalAuthenticate :show="modal" @reset="modal = false" />
    <Dialog v-model:visible="contactDialog" modal header="Ask a Question" >
        <ContactForm />
        <template #footer>
            <Button label="Submit" outlined severity="secondary" @click="visible = false" autofocus />
        </template>
    </Dialog>

    <div class="bg-white h-20 shadow z-50">
        <div class="flex items-center h-full m-auto lg:w-5/6 xl:w-11/12 2xl:w-7/12">
            <div id="left" class="mr-auto">
                <SvgLogo />
            </div>
            <IconField v-if="!route().current('main')" iconPosition="left">
                <InputIcon class="pi pi-search" />
                <InputText 
                    placeholder="Search" 
                    class="rounded-lg" 
                    @focusin="mask = true" @focusout="mask = false"
                />
            </IconField>
            <div class="flex gap-4 mx-auto">
                <NavLink label="Explore" :href="route('explore.index')" :active="route().current('explore.index')" />
                <NavLink label="Community" :href="route('community')" :active="route().current('community')" />
            </div>
            <div class="flex gap-4">
                <RoundedButton class="flex gap-2" @click="contactDialog = true">
                    <i class="pi pi-info-circle"></i>Contact Us
                </RoundedButton>
                <RoundedButton class="flex gap-2" @click="directToBookmark">
                    <i class="pi pi-bookmark"></i>Bookmark
                </RoundedButton>
                <!-- <i v-badge="2" class="pi pi-bookmark" style="font-size: 2rem" /> -->
                <RoundedButton v-if="!$page.props.auth.user" class="flex gap-2" @click="modal = true">
                    <i class="pi pi-sign-in"></i>Sign In
                </RoundedButton>
                <SplitButton 
                    v-else 
                    :model="items" 
                    :label="$page.props.auth.user.name" 
                    severity="secondary" 
                    rounded 
                    @click="$inertia.get(route('profile.account'))" 
                />
            </div>
        </div>
    </div>
    <div v-if="mask" class="absolute bg-black/50 h-screen w-full z-40"></div>
</template>