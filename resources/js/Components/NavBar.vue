<script setup>
import NavLink from '@/Components/NavLink.vue';
import RoundedButton from '@/Components/RoundedButton.vue'
import ModalAuthentication from '@/Components/Modal/ModalAuthentication.vue';
import SplitButton from 'primevue/splitbutton';
import { computed, ref, watch } from 'vue';
import { router, usePage } from '@inertiajs/vue3';

const props = defineProps({
    isAuthenticated: {
        type: Boolean,
    }
});

const modal = ref(false);

watch(() => props.isAuthenticated, (newValue) => {
    modal.value = !newValue;
});

const reactiveModal = computed(() => modal.value);

const user = computed(() => usePage().props.auth.user);

const profile = () => {
    // direct user to profile page
}

const items = [
    {
        label: 'Log Out',
        command: () => router.delete(route('auth.destroy')),
    }
]

</script>

<template>
    <ModalAuthentication :show="reactiveModal" @reset="modal = false" />

    <div class="bg-white h-20 shadow">
        <div class="flex items-center h-full m-auto lg:w-5/6 xl:w-11/12 2xl:w-7/12">
            <div id="left" class="mr-auto">
                <SvgLogo />
            </div>
            <div class="flex gap-4">
                <NavLink label="Package" :href="route('tour.index')" :active="route().current('package.index')"></NavLink>
                <NavLink label="Explore" :href="route('explore.index')" :active="route().current('explore.index')"></NavLink>
            </div>
            <div class="flex gap-4 ml-auto">
                <RoundedButton>Bookmark</RoundedButton>
                <RoundedButton v-if="!user" @click="modal = true">Sign In</RoundedButton>
                <SplitButton v-else label="Profile" severity="secondary" rounded @click="profile" :model="items" />
            </div>
        </div>
    </div>
</template>