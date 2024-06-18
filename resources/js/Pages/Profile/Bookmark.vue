<script setup>
import ProfileLayout from '@/Layouts/ProfileLayout.vue';
import Card from 'primevue/card';
import Button from 'primevue/button';
import Message from 'primevue/message';

const props = defineProps({
    bookmarks: {
        type: [Array, Object],
    }
});

</script>

<template>
    <ProfileLayout section="Bookmark">
        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <h1>My Bookmarks</h1>
                <Message v-if="!$page.props.auth.user" :closable="false">Your bookmarks are saved temporarily and will disappear if you do not save them to your account. Login or create a new account now!</Message>
                <div v-if="bookmarks.length != 0" class="grid gird-cols-2 lg:grid-cols-3 gap-8">
                    <Card 
                        v-for="bookmark in bookmarks" 
                        pt:root:class="outline outline-1 outline-neutral-200 shadow-md"
                        class="relative"
                    >
                        <template #header>
                            <img alt="user header" class="rounded-t-lg aspect-video object-cover"
                                src="https://static.travelstride.com/store/51/ad4f08f81843578f3ea235b037593e/b669206bb8425f9409b22d1a87c8d181.jpg" 
                            />
                            <span class="group absolute top-0 right-0 p-3 cursor-pointer">
                                <div class="group-hover:bg-white bg-black/50 rounded-full size-8 flex justify-center items-center" 
                                    @click="$inertia.delete(route('bookmark.destroy', bookmark.id, { preserveScroll: true }));">
                                    <i class="group-hover:text-black pi pi-bookmark-fill text-white"></i>
                                </div>
                            </span>
                        </template>
                        <template #title>
                            <span class="line-clamp-2">{{ bookmark.tour.name }}</span>
                        </template>
                        <!-- <template #subtitle></template> -->
                        <template #content>
                            <p class="line-clamp-3">{{ bookmark.tour.description }}</p>
                        </template>
                        <template #footer>
                            <div class="gap-3 mt-1">
                                <Button 
                                    label="View Tour"
                                    severity="secondary"
                                    class="w-full"
                                    @click="$inertia.get(route('tour.show', bookmark.tour.id))"
                                />
                            </div>
                        </template>
                    </Card>
                </div>
                <div v-else>
                    No bookmarks yet...
                </div>
            </div>
        </div>
    </ProfileLayout>
</template>