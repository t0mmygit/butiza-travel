<script setup>
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import Divider from 'primevue/divider';

import ProfileLayout from '@/Layouts/ProfileLayout.vue';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});
</script>

<template>
    <ProfileLayout section="Settings">
        <div class="flex max-w-7xl mx-auto sm:px-6 lg:px-8 py-8 gap-8">
            <div class="flex-1">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                        class="max-w-xl"
                        />
                    <Divider />
                    <UpdatePasswordForm class="max-w-xl" />
                </div>
            </div>
        </div>
    </ProfileLayout>
</template>
