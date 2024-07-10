<script setup>
import Checkbox from '@/Components/Checkbox.vue';

import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';
dayjs.extend(relativeTime);

defineProps({
    notifications: {
        type: [Array, Object],
    },
});

</script>

<template>
    <div v-if="notifications" v-for="notification in notifications" class="w-full border border-neutral-300 rounded-md p-4">
        <div class="flex items-center">
            <div>
                <Checkbox />
            </div>
            <div class="flex flex-col">
                <div class="flex gap-4">
                    <strong>Sender Name</strong>
                    <div class="flex items-center font-thin text-sm text-neutral-400 gap-4"> 
                        <span>{{ notification.data.subject }}</span>
                        <span>{{ dayjs(notification?.created_at).fromNow() }}</span>
                    </div>
                </div>
                <span>{{ notification.data?.message }}</span>
            </div>
        </div>
    </div>
    <div v-else>
        Nothing to see here...
    </div>
</template>