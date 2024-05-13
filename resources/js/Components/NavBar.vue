<script setup>
import NavLink from '@/Components/NavLink.vue';
import RoundedButton from './RoundedButton.vue';
import Modal from './Modal.vue';
import ModalEmailSection from '@/Components/SignUpModal/ModalEmailSection.vue';
import ModalDetailSection from '@/Components/SignUpModal/ModalDetailSection.vue';

import { ref } from 'vue';

const emailModal = ref(false);
const detailModal = ref(false);

const confirm = () => {
    emailModal.value = false;
    detailModal.value = true;
}

const back = () => {
    emailModal.value = true;
    detailModal.value = false;
}

</script>

<template>
    <div class="bg-primary-200 h-20">
        <div id="content" class="flex items-center h-full m-auto lg:w-5/6 xl:w-11/12 2xl:w-7/12">
            <div id="left" class="mr-auto">
                <a :href="route('main')">Logo</a>
            </div>
            <div id="middle" class="flex gap-4">
                <NavLink label="Package" :href="route('package.index')" :active="route().current('package.index')"></NavLink>
                <NavLink label="Explore" :href="route('explore.index')" :active="route().current('explore.index')"></NavLink>
            </div>
            <div id="right" class="flex gap-4 ml-auto">
                <RoundedButton>Bookmark</RoundedButton>
                <RoundedButton @click="emailModal = true">Sign Up</RoundedButton>
            </div>
        </div>
    </div>

    <Modal 
        :show="emailModal"
        @close="emailModal = false"
    >
        <ModalEmailSection @confirm="confirm" />
    </Modal>

    <Modal 
        :show="detailModal"
        allowPrevious
        @previous="back"
        @close="detailModal = false"
    >
        <ModalDetailSection @previous="back" />
    </Modal>
</template>