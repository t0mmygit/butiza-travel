<script setup>
import TextInput from '@/Components/TextInput.vue';

import Button from 'primevue/button';
import Divider from 'primevue/divider';
</script>

<template>
    <div class="flex flex-col gap-8">
        <div class="border border-neutral-300 rounded-md p-4">
            <header>
                <h2>Account Information</h2>
                <p class="mt-1 text-sm text-neutral-600">
                    Update your account's profile information.
                </p>
                <Divider />
                <form>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <TextInput
                            label="First Name"
                        />
                        <TextInput
                            label="Last Name"
                        />
                        <TextInput
                            label="Email Address"
                        />
                        <TextInput
                            label="Phone Number"
                        />
                        <TextInput
                            label="Business Name"
                        />
                        <TextInput
                            label="Website"
                        />
                        <TextInput
                            label="Country"
                        />
                        <TextInput
                            label="City"
                        />
                    </div>
                    <div class="flex justify-end">
                        <Button label="Save Changes" class="mt-4" />
                    </div>
                </form>
            </header>
        </div>
        <div class="border border-neutral-300 rounded-md p-4">
            <header>
                <h2>Verification Status</h2>
                <p class="mt-1 text-sm text-neutral-600">
                    Your account's verification status.
                </p>
            </header>
        </div>
    </div>
</template>