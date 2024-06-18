<script setup>
import Chirp from '@/Components/Community/Chirp.vue';
import ChirpTopSection from '@/Components/Community/ChirpTopSection.vue';
import ChirpGroupTour from '@/Components/Community/ChirpGroupTour.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputError from '@/Components/InputError.vue';

import Button from 'primevue/button';
import Badge from 'primevue/badge';
import Avatar from 'primevue/avatar';
import Textarea from 'primevue/textarea';

import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';
import axios from 'axios';

import { router, useForm, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

dayjs.extend(relativeTime);

const props = defineProps({
    posts: {
        type: [Array, Object]
    }
});

const posts = computed(() => props.posts.data);

const nextCursor = ref(props.posts.next_cursor);
const filterType = ref(null);
const isLoading = ref(false);
const visiblePost = ref(false);
const authModal = ref(false);

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

const loadPost = async () => {
    if (isLoading.value || !nextCursor.value) return;
    isLoading.value = true;

    try {
        const response = await axios.get('/community', {
            params: {
                type: filterType.value,
                cursor: nextCursor.value
            },
            headers: {
                'Accept': 'application/json'
            }
        });
        posts.value.push(...response.data.data);
        nextCursor.value = response.data.next_cursor;
    } catch (error) {
        console.error(error);
    } finally {
        isLoading.value = false;
    }
};

const createPost = () => {
    if (!usePage().props.auth.user?.id) authModal.value = true;
    else visiblePost.value = !visiblePost.value;
};

const createGroupTour = () => {
    if (!usePage().props.auth.user?.id) authModal.value = true;
    else router.get(route('group-tour.index'));
}

const form = useForm({
    user_id: usePage().props.auth.user?.id,
    content: null,
});

const onScroll = () => {
    if (
        window.innerHeight + window.scrollY >= 
        document.body.offsetHeight - 500 && !isLoading.value
    ) {
        loadPost();
    }
};

window.addEventListener('scroll', onScroll);

</script>

<template>
    <ModalAuthenticate :show="authModal" @reset="authModal = false" />

    <div v-if="!$page.props.auth.user" class="fixed w-full h-20 bg-primary left-0 bottom-0">
        <div class="flex justify-center items-center h-full">
            <h2 class="text-white mr-4">Join the community and find your travel partners!</h2>
            <PrimaryButton class="bg-neutral-800" @click="authModal = true">Sign Up</PrimaryButton>
        </div>
    </div>

    <div class="flex lg:max-w-6xl mx-auto">
        <section class="flex flex-row w-full min-h-dvh justify-center">
            <header class="sticky flex-none items-end">
                <div class="flex m-2 justify-center shadow rounded px-2" @click="route('home')">
                    <small class="flex cursor-pointer py-2 items-center gap-2">
                        <i class="pi pi-angle-left"></i>
                        Exit Community
                    </small>
                </div>
                <div v-if="$page.props.auth.user">
                    <div class="flex justify-center items-center mb-4 pl-2 py-3 cursor-pointer" @click="route('profile.account')">
                        <Avatar icon="pi pi-user" class="mr-2" shape="circle" />
                        <h2>{{ $page.props.auth.user.name }}</h2>
                    </div>
                    <nav class="flex flex-col gap-1">
                        <Button
                            v-for="nav in navButton"
                            :label="nav.label"
                            :icon="nav.icon"
                            class="hover:bg-neutral w-full text-left"
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
                                <Button label="Create Post" outlined class="flex-1" @click="createPost" />
                                <Button label="Create Group Tour" outlined class="flex-1" @click="createGroupTour" />
                            </div>
                            <Chirp v-if="visiblePost" class="flex">
                                <Avatar icon="pi pi-user" class="mr-2 mt-1 flex-none" shape="circle" />
                                <form @submit.prevent="form.post(route('post.store'), { onSuccess: () => form.reset() })" class="w-full">
                                    <div class="flex flex-col w-full">
                                        <Textarea
                                            v-model="form.content"
                                            rows="1" autoResize
                                            placeholder="Share your experience..."
                                            class="mb-2 shadow-none"
                                            :invalid="form.errors.content ? true : false"
                                        />
                                        <InputError :message="form.errors.content" />
                                        <div class="flex justify-between">
                                            <div class="flex">
                                                <Button icon="pi pi-image" text rounded aria-label="Image" />
                                                <Button icon="pi pi-face-smile" text rounded aria-label="Emoji" />
                                            </div>
                                            <Button
                                                type="submit"
                                                label="Post"
                                                rounded class="px-6"
                                            />
                                        </div>
                                    </div>
                                </form>
                            </Chirp> 
                            <!-- Chirp Main Section -->
                            <Chirp v-if="posts.length" v-for="post in posts">
                                <!-- Group Tour Post -->
                                <section v-if="post.type === 'group_tour'">
                                    <ChirpTopSection :post="post" />
                                    <ChirpGroupTour
                                        :post="post"
                                        image="https://static.travelstride.com/store/map_image/5061423/attachment/2a1bc8851483009a6ce5dce769eb39dd.jpg"
                                    />
                                    <p class="mb-2">{{ post.description }}</p>
                                </section>
                                <!-- Normal Post -->
                                <section v-if="post.type === 'normal_post'">
                                    <ChirpTopSection :post="post" />
                                    <p>{{ post.content }}</p>
                                </section>
                                <div class="flex justify-between">
                                    <div class="flex gap-2 items-center">
                                        <Button icon="pi pi-heart" severity="secondary" text rounded aria-label="Like" />
                                        <Button icon="pi pi-bookmark" severity="secondary" text rounded aria-label="Bookmark" />
                                        <Button icon="pi pi-share-alt" severity="secondary" text rounded aria-label="Share" />
                                    </div>
                                </div>
                            </Chirp>
                            <div v-else class="text-center p-4">
                                <p>Nothing here? Be the first to share your adventure!</p>
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