<script setup>
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';

import Textarea from 'primevue/textarea';
import Button from 'primevue/button';
import Rating from 'primevue/rating';

import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const emit = defineEmits(['close-review-dialog']);

const props = defineProps({
    booking: {
        type: [Array, Object],
    }
});


const form = useForm({
    booking_id: props.booking.id,
    rating: null,
    title: null,
    content: null,
});

const submitForm = () => {
    form.post(route('review.store',  props.booking.tour.id))
};

</script>

<template>
    <p class="mb-6">How was your trip?</p>
    <form @submit.prevent="submitForm">
        <div class="space-y-6">
            <!-- <div class="flex align-items-center gap-4 mb-3">
                <TextInput v-model="form.ratings" label="Rate Your Trip" :error="form.errors.ratings" />
            </div> -->
            <div>
                <div class="flex justify-center shadow rounded py-3 mb-3 w-full outline outline-1 outline-neutral-300">
                    <Rating v-model="form.rating" :cancel="false" />
                </div>
                <InputError :message="form.errors.rating" />
            </div>
            <TextInput v-model="form.title" label="Review Summary" :error="form.errors.title" />
            <div class="flex flex-col mb-5">
                <label class="text-neutral-500 text-sm mb-1 ml-2">Your Review</label>
                <Textarea v-model="form.content" :invalid="form.errors.content ? true : false" rows="5" />
                <InputError :message="form.errors.content" />
            </div>
        </div>
        <div class="flex justify-end gap-2">
            <Button type="submit" label="Submit" />
        </div>
    </form>
</template>