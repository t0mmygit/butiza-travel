<script setup>
import ProfileLayout from '@/Layouts/ProfileLayout.vue';
import ProfileEdit from '@/Pages/Profile/Edit.vue';
import Avatar from 'primevue/avatar';
import dayjs from 'dayjs';

const props = defineProps({
    user: {
        type: [Array, Object],
        required: true
    }
});

const stats = [
    {
        label: props.user.posts.length > 1 ? 'Posts' : 'Post',
        value: props.user.posts.length,
    },
    {
        label: props.user.reviews.length > 1 ? 'Reviews' : 'Review',
        value: props.user.reviews.length,
    },
    {
        label: props.user.bookings.length > 1 ? 'Adventures' : 'Adventure',
        value: props.user.bookings.status === 'completed' ? props.user.bookings.length : 0,
    }
];

</script>

<template>
    <ProfileLayout section="Account">
        <div class="flex max-w-7xl mx-auto sm:px-6 lg:px-8 py-8 gap-8">
            <div class="flex-1">
                <!-- User Information -->
                <div class="flex flex-col items-center p-4 sm:p-6 bg-white shadow sm:rounded-lg">
                    <div class="flex flex-col items-center mb-6">
                        <Avatar icon="pi pi-user" size="xlarge" shape="circle" class="size-40 mb-2" />
                        <h1>{{ user.name }}</h1>
                        <span class="text-sm mb-2">Joined since <strong>{{ dayjs(user.created_at).format('DD MMMM YYYY') }}</strong></span>
                        <p class="text-center">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                    </div>
                    <div class="flex w-full gap-6">
                        <div v-for="stat in stats" class="flex flex-1 flex-col items-center justify-center aspect-square shadow sm:rounded-lg p-2 sm:p-4">
                            <h1>{{ stat.value }}</h1>
                            <span>{{ stat.label }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex-1">
                <ProfileEdit />
            </div>
        </div>
    </ProfileLayout>
</template>