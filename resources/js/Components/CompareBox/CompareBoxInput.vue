<script setup>

defineProps({
    tour: {
        type: Object,
        required: true
    }
});
</script>

<template>
    <div id="input">
        <div class="bg-slate-200 px-2.5 pt-2.5 pb-2 flex justify-between">
            <span v-if="!tour.name" class="font-medium text-gray-500 ml-0.5">
                Select first tour
            </span>
            <span v-else class="font-medium ml-0.5">
                {{ tour.name }}
            </span>
            <div id="remove-icon" @click="$emit('remove-tour', tour)">
                <svg v-if="tour.name" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" 
                    class="size-6 text-gray-500 hover:text-red-500 cursor-pointer">
                    <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm3 10.5a.75.75 0 0 0 0-1.5H9a.75.75 0 0 0 0 1.5h6Z" clip-rule="evenodd" />
                </svg>
            </div>
        </div>
        <div class="bg-gray-400 h-0.5"></div>

         <!-- <div v-for="(object, index) in objects" :key="index" id="input">
                <div class="bg-slate-200 px-2.5 pt-2.5 pb-2 flex justify-between">
                <span v-if="!object.name" class="font-medium text-gray-500 ml-0.5">
                    Select tour {{ index + 1 }}
                </span>
                <span v-else class="font-medium ml-0.5">
                    {{ object.name }}
                </span>
                <div id="remove-icon" class="relative" @click="$emit('remove-compare-tour', object)">
                    <svg v-if="object.name" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6 text-gray-500 hover:text-red-500 cursor-pointer">
                    <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm3 10.5a.75.75 0 0 0 0-1.5H9a.75.75 0 0 0 0 1.5h6Z" clip-rule="evenodd" />
                    </svg>
                </div>
                </div>
                <div class="bg-gray-400 h-0.5"></div>
            </div> -->
    </div>
</template>