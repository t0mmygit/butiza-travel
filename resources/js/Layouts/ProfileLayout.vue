<script setup>
import NavBar from '@/Components/NavBar.vue';
import TabMenu from 'primevue/tabmenu';

import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    section: {
        type: String,
        required: true,
    }
});

const active = ref(props.section);
const headTitle = ref(null);

const items = [
    {
        label: 'Account',
        icon: 'pi pi-user',
        command: () => router.get(route('profile.account'))
    },
    {
        label: 'History',
        icon: 'pi pi-history',
        command: () => router.get(route('profile.history'))
    },
    {
        label: 'Bookmark',
        icon: 'pi pi-bookmark',
        command: () => router.get(route('profile.bookmark'))
    },
    {
        label: 'Review',
        icon: 'pi pi-star'
    }
];

const currentActive = (currentActive) => {
    switch(currentActive) {
        case 'Account':
            headTitle.value = items[0].label;
            return 0;
        case 'History':
            headTitle.value = items[1].label;
            return 1;
        case 'Bookmark':
            headTitle.value = items[2].label;
            return 2;
        case 'Review': 
            headTitle.value = items[3].label;
            return 3;
    }
}

</script>

<template>
    <Head :title="headTitle" />

    <NavBar />
    <div v-if="$page.props.auth.user" class="bg-neutral">
        <div class="max-w-7xl sm:px-6 lg:px-8 mx-auto">
            <TabMenu 
                :model="items" 
                :activeIndex="currentActive(props.section)"
                pt:menu:class="bg-transparent"
                pt:action:class="bg-transparent"
            />
        </div>
    </div>
    
    <slot />
</template>