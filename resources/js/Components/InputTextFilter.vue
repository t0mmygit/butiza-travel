<script setup>
import { computed, ref, watchEffect } from 'vue';
import TextInput from './TextInput.vue';

const emit = defineEmits(['filter']);

const filters = ref([]);
const input = ref();

const props = defineProps({
    column: {
        type: String,
        required: true
    },
});

const filter = (event) => {
    if (!event.target.value) return
    
    const exist = filters.value.some(filter => filter === event.target.value)
    if (!exist) filters.value.push(event.target.value);

    input.value = '';
    emit('filter', event.target.value, props.column);
}

const remove = (destination) => {
    // [1] Find the index for the selected tour
    const index = filters.value.findIndex(filter => {
        return filter === destination;
    })

    // [2] Then, delete remove from 'items' array
    if (index != -1) {
        filters.value.splice(index, 1)
        emit('filter', destination, props.column)    
    } 
};

</script>

<template>
    <TextInput 
        @keyup.enter="filter"
        v-model="input"
        placeholder="Enter destination..." 
    />
    <div v-for="filter in filters" class="bg-indigo-100 rounded-3xl w-fit px-4 py-2 flex items-center justify-between gap-2">
        {{ filter }}
        <span class="cursor-pointer overflow-hidden" @click="remove(filter)">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4">
                <path fill-rule="evenodd" d="M5.47 5.47a.75.75 0 0 1 1.06 0L12 10.94l5.47-5.47a.75.75 0 1 1 1.06 1.06L13.06 12l5.47 5.47a.75.75 0 1 1-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 0 1-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
            </svg>
        </span>
    </div>
</template>
