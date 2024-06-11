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

const openDetailModal = (data) => {
    emailModal.value = false;
    detailModal.value = true;
    email.value = data;
}

const detailBackToEmail = () => {
    detailModal.value = false;
    emailModal.value = true;
}

const passwordBackToEmail = () => {
    passwordModal.value = false;
    emailModal.value = true;
}

const openPasswordModal = (data) => {
    emailModal.value = false;
    passwordModal.value = true;
    email.value = data;
}

</script>

<template>
    <Modal 
        :show="emailModal" 
        @close="emailModal= false; $emit('reset')"
    >
        <ModalEmailSection @confirm="openDetailModal" @password="openPasswordModal" />
    </Modal>

    <Modal 
        :show="passwordModal" 
        @previous="passwordBackToEmail" allowPrevious
        @close="passwordModal = false; $emit('reset')"
    >
        <ModalPasswordSection 
            :email="email" 
            @close="passwordModal = false; $emit('reset')"
        />
    </Modal>

    <Modal 
        :show="detailModal"
        @previous="detailBackToEmail" allowPrevious
        @close="detailModal = false; $emit('reset')"
    >
        <ModalDetailSection 
            :email="email"
            @close="passwordModal = false; $emit('reset')"
        />
    </Modal>
</template>