<script setup>
import Chirp from '@/Components/Community/Chirp.vue';
import GroupTourChirp from '@/Components/Community/GroupTourChirp.vue';
import RoundedButton from '@/Components/RoundedButton.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

import Button from 'primevue/button';
import Badge from 'primevue/badge';
import Avatar from 'primevue/avatar';
import Textarea from 'primevue/textarea';
import Tag from 'primevue/tag';

import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';
import { useForm, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

dayjs.extend(relativeTime);

const props = defineProps({
    posts: {
        type: [Array, Object]
    }
});

console.log(props.posts);

const posts = computed(() => props.posts.data);
const filterType = ref(null);

const page = usePage();

const visiblePost = ref(false);

const navButton = [
    {
        label: "Home",
        icon: 'pi pi-home'
    },
    {
        label: 'Bookmarks',
        icon: 'pi pi-bookmark'
    },
    {
        label: 'Notifications',
        icon: 'pi pi-bell'
    },
    {
        label: 'Group Tour',
        icon: 'pi pi-users'
    },
    {
        label: 'Settings',
        icon: 'pi pi-cog'
    },
];

const filterSelections = [
    {
        label: 'All',
    }, 
    {
        label: 'Post'
    },
    {
        label: 'Group Tour'
    }
];

const communityGuidelines = [
  { 
    header: "Respect and Courtesy",
    paragraph: "Treat all members with respect and kindness."
  },
  { 
    header: "Relevance",
    paragraph: "Keep your posts and comments relevant to travel and tourism."
  },
  { 
    header: "Original Content",
    paragraph: "Share your own photos, stories, and experiences whenever possible."
  },
  { 
    header: "Safety First",
    paragraph: "Prioritize safety in all your travel recommendations and advice."
  },
  { 
    header: "No Spam or Self-Promotion",
    paragraph: "Avoid spamming the community with advertisements or irrelevant links."
  },
  { 
    header: "Be Mindful of Language and Tone",
    paragraph: "Use respectful language and maintain a positive tone."
  },
  { 
    header: "Respect Privacy",
    paragraph: "Protect the privacy of others and refrain from sharing personal information without consent."
  },
  { 
    header: "Report Inappropriate Content",
    paragraph: "If you come across any content that violates these guidelines, please report it to the moderators."
  }
];

function tags(type) {
    switch(type) {
        case 'post':
            return 'Post';
        case 'group_tour':
            return 'Group Tour';
    }
}

const form = useForm({
    user_id: page.props.auth.user?.id,
    content: null,
});

</script>

<template>
    <!-- <div class="bg-white h-fit shadow py-6">
        <div class="max-w-sm lg:max-w-full lg:mx-16 flex items-center relative">
            <SvgLogo />
        </div>
    </div> -->

    <div v-if="!$page.props.auth.user" class="fixed w-full h-20 bg-primary left-0 bottom-0">
        <div class="flex justify-center items-center h-full">
            <h2 class="text-white mr-4">Join the community and find your travel partners!</h2>
            <PrimaryButton class="bg-neutral-800">Sign Up</PrimaryButton>
        </div>
    </div>

    <!-- <section class="mx-auto my-8 lg:flex xl:flex max-w-2xl xl:max-w-7xl"> -->
    <div class="flex lg:max-w-6xl mx-auto">
        <section class="flex flex-row w-full min-h-dvh justify-center">
            <header class="sticky flex-none items-end">
                <div class="flex m-2 justify-center" @click="$inertia.get(route('main'))">
                    <small class="flex cursor-pointer py-2 items-center gap-2">
                        <i class="pi pi-angle-left"></i>
                        Exit Community
                    </small>
                </div>
                <div v-if="$page.props.auth.user">
                    <div class="flex items-center mb-2 pl-2 py-2 rounded w-full">
                        <Avatar icon="pi pi-user" class="mr-2" shape="circle" />
                        <h2>{{ $page.props.auth.user.name }}</h2>
                    </div>
                    <nav class="flex flex-col gap-1">
                        <Button
                            v-for="nav in navButton"
                            :label="nav.label"
                            :icon="nav.icon"
                            class="hover:bg-neutral-300 w-full text-left"
                            plain text
                        />
                    </nav>
                </div>
            </header>
    
            <main class="flex items-start flex-grow min-h-full w-full">
                <div class="flex w-full h-full justify-between gap-4">
                    <section class="flex-1 border border-primary border-y-0 bg-white">
                        <div class="bg-white h-12 flex justify-between">
                            <div v-for="selection in filterSelections" class="flex flex-1 text-center justify-center items-center cursor-pointer border-b border-primary hover:bg-neutral-300">
                                <a>{{ selection.label }}</a>
                            </div>
                        </div>
                        <div class="p-3">
                            <div class="flex gap-2 mb-4">
                                <Button label="Create Post" outlined class="flex-1" @click="visiblePost = !visiblePost" />
                                <Button label="Create Group Tour" outlined class="flex-1" @click="$inertia.get(route('host.index'))" />
                            </div>
                            <Chirp v-if="visiblePost" class="flex">
                                <Avatar icon="pi pi-user" class="mr-2 mt-1 flex-none" shape="circle" />
                                <div class="flex flex-col w-full">
                                    <Textarea placeholder="Share your experience..." rows="1" autoResize class="mb-2 border-none shadow-none" />
                                    <div class="flex justify-between">
                                        <div class="flex">
                                            <Button icon="pi pi-image" text rounded aria-label="Image" />
                                            <Button icon="pi pi-face-smile" text rounded aria-label="Emoji" />
                                        </div>
                                        <Button label="Post" rounded class="px-6" @click="form.post(route('post-store'))" />
                                    </div>
                                </div>
                            </Chirp> 
                            <Chirp v-if="posts != null" v-for="post in posts">
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
                                            <DropdownLink href="#" as="button">
                                                Delete
                                            </DropdownLink>
                                        </template>
                                    </Dropdown>
                                </div>
                                <GroupTourChirp
                                    :post="post"
                                    image="https://static.travelstride.com/store/map_image/5061423/attachment/2a1bc8851483009a6ce5dce769eb39dd.jpg" 
                                />
                                <p class="mb-2">{{ post.description }}</p>
                                <div class="flex justify-between">
                                    <div class="flex gap-2 items-center">
                                        <Button icon="pi pi-heart" severity="secondary" text rounded aria-label="Like" />
                                        <Button icon="pi pi-bookmark" severity="secondary" text rounded aria-label="Bookmark" />
                                        <Button icon="pi pi-share-alt" severity="secondary" text rounded aria-label="Share" />
                                    </div>
                                </div>
                            </Chirp>
                            <div v-else class="text-center p-4">
                                <p>Nothing here? Well that's embarassing...</p>
                            </div>
                        </div>
                    </section>
                    <section class="flex-none max-w-xs px-4 py-3">
                        <div class="p-2 mb-4">
                            <SvgLogo />
                        </div>
                        <h1 class="mb-4">Community Guideline</h1>
                        <div v-for="guideline in communityGuidelines" :key="guideline.id" class="mb-4">
                            <div class="flex items-center gap-2">
                                <Badge />
                                <h3>{{ guideline.header }}</h3>
                            </div>
                            <p>{{ guideline.paragraph }}</p>
                        </div>
                    </section>
                </div>
            </main>
        </section>
    </div>
</template>