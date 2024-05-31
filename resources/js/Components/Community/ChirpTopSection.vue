<script setup>
import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';
dayjs.extend(relativeTime);

import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';

import Tag from 'primevue/tag';
import Avatar from 'primevue/avatar';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    post: {
        type: [Array, Object],
        required: true
    }
});

function tags(type) {
    switch(type) {
        case 'normal_post':
            return 'Post';
        case 'group_tour':
            return 'Group Tour';
    }
}

</script>

<template>
    <div class="flex items-center mb-2 justify-between">
        <div class="flex items-center">
            <Avatar icon="pi pi-user" class="mr-2" shape="circle" />
            <h3>{{ post.user.name }}</h3>
            <small class="ml-2">{{ dayjs(post.created_at).fromNow() }}</small>
            <small v-if="post.created_at !== post.updated_at"> &middot; edited</small>
            <Tag v-if="post.type" :value="tags(post.type)" class="ml-2" />
        </div>
        <Dropdown v-if="post.user.id === $page.props.auth.user?.id" class="mr-2">
            <template #trigger>
                <button>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                    </svg>
                </button>
            </template>
            <template #content>
                <button
                    class="block w-full px-4 py-2
                    text-left text-sm leading-5 text-gray-700
                    hover:bg-gray-100 focus:bg-gray-100
                    transition duration-150 ease-in-out"
                >
                    Edit
                </button>
                <DropdownLink as="button" :href="route('post.destroy', post.id)" method="delete">
                    Delete
                </DropdownLink>
            </template>
        </Dropdown>
    </div>
</template>