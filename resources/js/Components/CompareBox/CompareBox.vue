<script setup>
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import CompareBoxInput from '@/Components/CompareBox/CompareBoxInput.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'

const emit = defineEmits(['remove-compare-tour']);

const props = defineProps({
    items: {
        value: Array
    }
});

const bodyVisible = ref(true);

const removeTour = (tour) => {
    emit('remove-compare-tour', tour)
}

const tours = computed(() => {
    const items = [];
    for (let i = 0; i < 3; i++) {
        if (props.items[i]) items.push(props.items[i]);
        else items.push({});
    };

    return items;
});

const compare = () => {
    let tour = [];

    for (let i = 0; i < tours.value.length; i++) {
        if (Object.entries(tours.value[i]).length != 0) tour.push(tours.value[i].id);
    };

    try {
        router.get(route('explore.show', {
            explore: tour,
        }));
    } catch (error) {
        console.error('Errors comparing tour:', error);
    }
};

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
                        <CompareBoxInput
                            v-for="tour in tours"
                            :tour="tour"
                            @remove-tour="removeTour"
                        />
                    </div>
                    <div id="footer" class="px-4 pb-6 mt-4">
                        <PrimaryButton :disabled="items.length < 2" @click="compare">
                            Compare
                        </PrimaryButton>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>