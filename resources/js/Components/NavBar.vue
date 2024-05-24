<script setup>
import NavLink from '@/Components/NavLink.vue';
import RoundedButton from './RoundedButton.vue';
import Modal from './Modal.vue';
import ModalEmailSection from '@/Components/Modal/ModalEmailSection.vue';
import ModalPasswordSection from '@/Components/Modal/ModalPasswordSection.vue';
import ModalDetailSection from '@/Components/Modal/ModalDetailSection.vue';

import { ref } from 'vue';

const emailModal = ref(false);
const passwordModal = ref(false);
const detailModal = ref(false);
const email = ref(null);

const confirm = (email) => {
    emailModal.value = false;
    detailModal.value = true;
    email.value = email;
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
    <div class="bg-white h-20 shadow">
        <div id="content" class="flex items-center h-full m-auto lg:w-5/6 xl:w-11/12 2xl:w-7/12">
            <div id="left" class="mr-auto">
                <SvgLogo />
            </div>
            <div id="middle" class="flex gap-4">
                <NavLink label="Package" :href="route('tour.index')" :active="route().current('package.index')"></NavLink>
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
        <ModalEmailSection @confirm="confirm" @password="password" />
    </Modal>

    <Modal 
        :show="passwordModal"
        @close="passwordModal = false"
    >
        <ModalPasswordSection :email="email" />
    </Modal>

    <Modal 
        :show="detailModal"
        allowPrevious
        @previous="back"
        @close="detailModal = false"
    >
        <ModalDetailSection 
            :email="email"
            @previous="back" 
        />
    </Modal>
</template>