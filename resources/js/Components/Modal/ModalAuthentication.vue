<script setup>
import Modal from '@/Components/Modal.vue';
import ModalEmailSection from '@/Components/Modal/ModalEmailSection.vue';
import ModalPasswordSection from '@/Components/Modal/ModalPasswordSection.vue';
import ModalDetailSection from '@/Components/Modal/ModalDetailSection.vue';
import { ref, watch } from 'vue';

const emit = defineEmits(['reset']);

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
        required: true
    }
});

watch(() => props.show, () => {
    emailModal.value = props.show;
});

const emailModal = ref(false);
const passwordModal = ref(false);
const detailModal = ref(false);
const email = ref(null);

const confirm = (data) => {
    emailModal.value = false;
    detailModal.value = true;
    email.value = data;
}

const back = () => {
    detailModal.value = false;
    emailModal.value = true;
}

const password = (data) => {
    emailModal.value = false;
    passwordModal.value = true;
    email.value = data;
}

</script>

<template>
    <Modal 
        :show="emailModal" 
        @close="$emit('reset'); emailModal = false"
    >
        <ModalEmailSection @confirm="confirm" @password="password" />
    </Modal>

    <Modal 
        :show="passwordModal" 
        @close="passwordModal = false; $emit('reset')"
    >
        <ModalPasswordSection :email="email" />
    </Modal>

    <Modal 
        :show="detailModal"
        allowPrevious
        @previous="back"
        @close="detailModal = false; $emit('reset')"
    >
        <ModalDetailSection :email="email" @previous="back" />
    </Modal>
</template>