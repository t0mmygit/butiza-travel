<script setup>
import { ref, watch } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
// import CompareBoxInput from '@/Components/CompareBoxInput.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'

const props = defineProps(['tours', 'items']);

const close = ref(true);
const bodyVisible = ref(true);
const first = ref([]);
const second = ref([]);

watch(props.items, (oldValue, newValue) => {
    if (newValue.length > 0) {
        close.value = false;
        first.value = props.items[0];
        second.value = props.items[1];

        if (props.items.length == 1) second.value = [];
    } else {
        first.value = []
    }
});

const compare = () => {
    let tours = []
    if (first.value && second.value) {
        tours = [first.value.id, second.value.id]
    } else {
        // Send a error message here!
        console.log('Not enough tours for comparison.');
        return;
    }

    try {
        router.get(route('explore.show', {
            explore: tours,
        }))
    } catch (error) {
        console.error('Errors comparing tour:', error)
    }
}

</script>

<template>
    <div v-if="items.length != 0" id="compare-container" class="lg:w-5/6 xl:w-10/12">
        <div class="bg-white fixed h-fit w-80 right-0 bottom-0 mr-8 mb-6 rounded-md shadow-xl">
            <div class="flex flex-col">
                <div id="header" class="bg-gray-200 p-4 flex justify-between rounded-md shadow">
                    <div id="title" class="flex items-center gap-1">
                        <h3 class="font-medium">Compare</h3>
                        <div v-if="props.items.length > 0" id="circle" class="bg-violet-500 rounded-full size-5 flex justify-center ">
                            <h3 class="text-white text-xs self-center">{{ props.items.length }}</h3>
                        </div>  
                    </div>
                    <div id="close" @click="bodyVisible = !bodyVisible">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                            <path v-if="bodyVisible" fill-rule="evenodd" d="M12.53 16.28a.75.75 0 0 1-1.06 0l-7.5-7.5a.75.75 0 0 1 1.06-1.06L12 14.69l6.97-6.97a.75.75 0 1 1 1.06 1.06l-7.5 7.5Z" clip-rule="evenodd" />
                            <path v-else fill-rule="evenodd" d="M11.47 7.72a.75.75 0 0 1 1.06 0l7.5 7.5a.75.75 0 1 1-1.06 1.06L12 9.31l-6.97 6.97a.75.75 0 0 1-1.06-1.06l7.5-7.5Z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                <div v-if="bodyVisible == true">
                    <div id="body" class="flex flex-col p-4 gap-4">

                        <!-- <CompareBoxInput
                            :tour="first"
                            @remove="remove"
                        /> -->

                        <div id="input">
                            <div class="bg-slate-200 px-2.5 pt-2.5 pb-2 flex justify-between">
                                <span v-if="!first.name" class="font-medium text-gray-500 ml-0.5">
                                    Select first tour
                                </span>
                                <span else class="font-medium ml-0.5">
                                    {{ first.name }}
                                </span>
                                <div id="remove-icon" class="relative"  @click="$emit('remove-compare-tour', first)">
                                    <svg v-if="first.name" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" 
                                        class="size-6 text-gray-500 hover:text-red-500 cursor-pointer">
                                        <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm3 10.5a.75.75 0 0 0 0-1.5H9a.75.75 0 0 0 0 1.5h6Z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                            <div class="bg-gray-400 h-0.5"></div>
                        </div>

                        <div id="input">
                            <div class="bg-slate-200 px-2.5 pt-2.5 pb-2 flex justify-between">
                                <span v-if="!second.name" class="font-medium text-gray-500 ml-0.5">
                                    Select second tour
                                </span>
                                <span else class="font-medium ml-0.5">
                                    {{ second.name }}
                                </span>
                                <div id="remove-icon" class="relative"  @click="$emit('remove-compare-tour', second)">
                                    <svg v-if="second.name" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" 
                                        class="size-6 text-gray-500 hover:text-red-500 cursor-pointer">
                                        <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm3 10.5a.75.75 0 0 0 0-1.5H9a.75.75 0 0 0 0 1.5h6Z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                            <div class="bg-gray-400 h-0.5"></div>
                        </div>

                    </div>
                    <div id="footer" class="px-4 pb-6 mt-4">
                        <PrimaryButton :disabled="items.length != 2" @click="compare">
                            Compare
                        </PrimaryButton>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>