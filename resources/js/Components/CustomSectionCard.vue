<script setup>
import Divider from 'primevue/divider';
import InputError from '@/Components/InputError.vue';
import Card from '@/Components/Card.vue';

defineProps({
    index: {
        type: [String, Number],
        required: true,
    },
    title: {
        type: String,
        required: true,
    },
    error: {
        type: String
    }
});

</script>

<template>
    <!-- <div class="bg-white flex flex-col p-5 rounded-lg shadow"> -->
        <Card>
            <div class="flex items-center gap-4">
                <div class="flex items-center justify-center text-white bg-primary size-8 rounded-md">
                    {{ index }}
                </div>
                <h1>{{ title }}</h1>
            </div>
            <Divider pt:root:class="my-2" />
            <div>
                <slot name="subtitle" />
            </div>
            <div class="flex flex-col gap-3">
                <slot />
            </div>
            <InputError :message="error" />
        </Card>
    <!-- </div> -->
</template>