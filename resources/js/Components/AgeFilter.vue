<script setup>
import { computed, ref } from 'vue';


// defineEmits(['filter'])
defineProps({
    column: {
        type: String,
        required: true
    }
})

const active = ref(null);

const ages = ['18 to 39', '30 +', '40 +'];

const onClick = (index) => {
    let age = ages[index];
    active.value = index;

    if (index == 0) age = age.split(' to ')
    else age = age.split(' +');

    emit('filter', age, props.column)
}

const getClasses = (index) => 
    active.value === index
        ? 'bg-indigo-500 text-white cursor-pointer rounded'
        : 'outline outline-1 outline-indigo-300';

</script>

<template>
    <div class="grid grid-cols-2 gap-4">
        <div v-for="(age, index) in ages" :id="index">
            <div @click="onClick(index)" 
                :class="getClasses(index)" 
                class="cursor-pointer rounded-md py-2 text-center"
            >
                {{ age }}
            </div>
        </div>
    </div>
</template>