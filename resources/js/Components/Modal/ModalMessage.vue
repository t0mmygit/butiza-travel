<script setup>
import Modal from '@/Components/Modal.vue';
import Button from 'primevue/button';

const emit = defineEmits(['cancel', 'close', 'trigger-action']);

const props = defineProps({
    show: {
        type: Boolean,
    },
    type: {
        type: String,
        required: true,
    },
    title: {
        type: String,
        required: true,
    },
    message: {
        type: String,
    }
});

function severity() {
    switch(props.type) {
        case 'cancel': return 'danger';
    }
}

const message = () =>
    props.message != null 
        ? props.message 
        : 'Are you sure you would like to do this?'

const cancel = () => emit('cancel');
const close = () => emit('close');
const action = () => emit('trigger-action');

</script>

<template>
    <Modal :show="show" @close="close">
        <div class="flex flex-col justify-center items-center p-6">
            <h2>{{ title }}</h2>
            <p class="mb-4">{{ message() }}</p>
            <div class="flex gap-4 w-full">
                <Button
                    label="Cancel"
                    severity="Plain" plain text outlined 
                    @click="cancel" 
                    class="flex-1 border-neutral-300"
                />
                <Button 
                    label="Confirm"
                    :severity="severity()"
                    @click="action"
                    class="flex-1"
                />
            </div>
        </div>
    </Modal>
</template>