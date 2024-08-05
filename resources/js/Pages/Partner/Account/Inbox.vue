<script setup>
import Checkbox from '@/Components/Checkbox.vue';

import Modal from '@/Components/Modal.vue';
import Divider from 'primevue/divider';
import Tag from 'primevue/tag';
import Avatar from 'primevue/avatar';

import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';
import { ref } from 'vue';
dayjs.extend(relativeTime);

const props = defineProps({
    notifications: {
        type: [Array, Object],
    },
});

const selectedNotification = ref(null);


function notificationSender(notification) {
    
}

function formattedNotificationMessage(message) {
    return message.replace(/\n/g, '<br />');
}

const viewNotification = notificationId => selectedNotification.value = props.notifications.find(notification => notification.id === notificationId);

</script>

<template>
    <Modal maxWidth="7xl" :show="selectedNotification != null" @close="selectedNotification = null" >
        <div class="px-4 py-6"> 
            <div class="flex gap-6">
                <span>From:</span>
                <Tag severity="primary">
                    <div class="flex gap-4 px-2"> 
                        <!-- <span>{{ notificationSender(selectedNotification.data.sender_id) }}</span> -->
                        <!-- <span class="font-normal">{{  }}</span> -->
                    </div>
                </Tag>
            </div>
            <Divider />
            <div>{{ selectedNotification.data.subject }}</div>
            <div  class="text-justify" v-html="formattedNotificationMessage(selectedNotification.data.body)"></div>
        </div>
    </Modal>

    <div 
        v-if="notifications.length" 
        v-for="notification in notifications"  
        :key="notification.id"
        class="w-full border border-neutral-300 rounded-md p-4 cursor-pointer"
    >
        <div class="flex items-center" @click="viewNotification(notification.id);">
            <div class="flex flex-col">
                <div class="flex gap-4">
                    <strong>Sender Name</strong>
                    <div class="flex items-center font-thin text-sm text-neutral-400 gap-4"> 
                        <span>{{ notification.data.subject }}</span>
                        <span>{{ dayjs(notification?.created_at).fromNow() }}</span>
                    </div>
                </div>
                <span class="line-clamp-1">{{ notification.data?.body }}</span>
            </div>
        </div>
    </div>
    <div v-else>
        Nothing to see here...
    </div>
</template>