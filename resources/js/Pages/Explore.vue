<script setup>
import { useFormatText } from '@/Composables/formatText';
import NavBar from '@/Components/NavBar.vue';
import Footer from '@/Components/Footer.vue';
import CheckBoxFilter from '@/Components/CheckBoxFilter.vue';
import DropdownFilter from '@/Components/DropdownFilter.vue';
import HorizontalCard from '@/Components/HorizontalCard.vue';
import InputTextFilter from '@/Components/InputTextFilter.vue';
import CompareBox from '@/Components/CompareBox/CompareBox.vue';
import MarginLayout from '@/Layouts/MarginLayout.vue';
import Button from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

import Slider from 'primevue/slider';
import Toast from 'primevue/toast';
import { useToast } from 'primevue/usetoast';

import { computed, onMounted, ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { TOAST_WARNING } from '@/constant';

const props = defineProps({
    tours: {
        type: Array
    },
    mode: {
        type: String
    },
    flash: {
        type: Object,
    }
});

const items = ref([]);
const currentFilter = ref([]);
const range = ref([1, 20]);
const toast = useToast();
const hostMode = ref(false);
const selectedHostTour = ref(null);

onMounted(() => {
    if (props.flash?.status != null || props.flash?.message != null) {
        toast.add({ 
            severity: props.flash.status, 
            summary: props.flash.status, 
            detail: props.flash.message, 
            life: 3000 
        });
    }
});

if (props.mode == "1") hostMode.value = true;
else hostMode.value = false; 

const handleToast = (severity, summary, detail, life = 6000) => {
    toast.add({ severity: severity, summary: summary, detail: detail, life: life })
}

const addCompareTour = (tour) => {
    const index = items.value.findIndex(item => item.id === tour.id);

    if (index != -1) items.value.splice(index, 1);
    else if (items.value.length >= 3) {
        handleToast(TOAST_WARNING, 'Limit Reached', 'You have reached the limit of 3 tours.');
    }
    else items.value.push(tour);
}

const removeCompareTour = (tour) => {
    const index = items.value.findIndex(item => item.id === tour.id);
    if (index != -1) items.value.splice(index, 1);
    else handleToast('warn', 'Something went wrong!', `Tour could not be found.`);
}

const addHostTour = (tour) => {
    if (selectedHostTour.value != tour) selectedHostTour.value = tour; 
    else selectedHostTour.value = null;
}

// Handle filtering logic
const filteredTours = computed(() => {
    if (currentFilter.value.length == 0) return props.tours;

    const tours = Object.values(props.tours);
    const filterObjects = Object.values(currentFilter.value);

    // Create an object to separate each filter category into an array of key-pair value
    const uniqueFilters = {};
    filterObjects.forEach(filterObject => {
        // [1] Get the filter's category name. (e.g. travel_intensity, duration)
        const filterCategory = filterObject['column'];
        // [2] Create an empty array for each filter category. (e.g. duration: [])
        if (!uniqueFilters[filterCategory]) uniqueFilters[filterCategory] = [];
        // [3] Add the filter to their respective filter category. (e.g. duration: [...])
        uniqueFilters[filterCategory].push(filterObject['filter']);
    });

    return tours.filter(tour => {
        return Object.entries(uniqueFilters).every(([column, filters]) =>
            filters.some(filter => {
                switch (column) {
                    case 'day':
                        return tour[column] >= filter.value[0] && tour[column] <= filter.value[1];
                    default:
                        return filter.includes(tour[column])
                } 
            })
        );
    });
});

const handleFilter = (value, column) => {
    const filterObject = { column: column, filter: value };

    let index;
    switch (column) {
        case 'day':
            index = currentFilter.value.findIndex(filter => filter.column === column);
            if (filterObject.filter.value[0] == 1 & filterObject.filter.value[1] == 20) currentFilter.value.splice(index, 1);
            else if (index != -1) currentFilter.value[index] = filterObject; 
            else currentFilter.value.push(filterObject);
            break;
        default:
            index = currentFilter.value.findIndex(filter => 
                filter.column === column && filter.filter === filterObject.filter);
            if (index != -1) currentFilter.value.splice(index, 1);
            else currentFilter.value.push(filterObject);
            break;
    }
}


const travelIntensity = {
    column: 'travel_intensity',
    value: ['relaxed', 'moderate', 'adventurous']   
};

</script>

<template>
    <MarginLayout>
        <NavBar />
        <Toast class="z-50" />
        <CompareBox 
            :items="items"
            @remove-compare-tour="removeCompareTour"
        />
        <div id="heading-container" class="bg-gradient-to-r from-primary-200 to-primary-400 font-sans py-4 shadow border w-full">
            <div id="heading" class="m-auto lg:w-11/12 xl:max-w-7xl">
                <h1 class="ml-4 lg:m-0 text-2xl font-bold">Explore Malaysia</h1>
            </div>
        </div>
        <section id="content-container" class="grid lg:grid-cols-8 lg:w-11/12 xl:max-w-7xl m-auto gap-6 my-4">
            <div id="filter" class="col-span-2 xl:block hidden">
                <div class="bg-primary-100 h-fit mb-4 rounded-md p-4 shadow">
                    <h3 class="font-bold text-xl">Applied Filters</h3>
                    <div v-if="currentFilter.length != 0" class="mt-4 gap-1 flex flex-col">
                        <div v-for="filter in currentFilter" class="flex justify-between">
                            <span v-if="filter.column == 'day'">
                                Duration is {{ filter.filter.value[0] }} - {{ filter.filter.value[1] }} days
                            </span>
                            <span v-else>
                                {{ useFormatText(filter.column, true) }} is {{ useFormatText(filter.filter) }}
                            </span>
                            <!-- Delete SVG -->
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-md h-fit shadow px-2 py-1">
                    <DropdownFilter label="Duration">
                        <div class="card flex flex-col justify-content-center">
                            <div class="flex justify-between mb-2 text-lg">
                                <span><strong>min.</strong> {{ range[0] }} day </span>
                                <span v-if="range[1] == 20">{{ range[1] }}+ days</span>
                                <span v-else><strong>max.</strong> {{ range[1] }} day</span>
                            </div>
                            <Slider 
                                v-model="range"
                                :min="1"
                                :max="20"
                                range class="h-2" 
                                pt:range:class="bg-black"
                                pt:startHandler:class="bg-white outline outline-black outline-1"  
                                pt:endHandler:class="bg-white outline outline-black outline-1"
                                @slideend="(event) => handleFilter(event, 'day')"
                            />
                        </div>
                    </DropdownFilter>
                    <DropdownFilter label="Travel Intensity">
                        <CheckBoxFilter 
                            :values="travelIntensity"
                            @filter="handleFilter" 
                        />
                    </DropdownFilter>
                    <!-- <DropdownFilter label="Price">
                    </DropdownFilter> -->
                    <DropdownFilter label="Destination">
                        <InputTextFilter 
                            column="destination" 
                            @filter="handleFilter"
                        />
                    </DropdownFilter>
                    <!-- <DropdownFilter label="Age">
                        <AgeFilter 
                            column="age"
                            @filter="handleFilter"
                        />
                    </DropdownFilter> -->
                </div>
            </div>
            <div class="col-span-6">
                <div class="h-fit">
                    <HorizontalCard
                        v-for="tour in filteredTours"
                        :key="tour.id"
                        :tour="tour"
                        :items="items"
                        :mode="hostMode"
                        @compare-tour="addCompareTour"
                        @host-tour="addHostTour"
                        @add-bookmark="handleToast"
                        @view-tour-failed="handleToast"
                    />
                </div>
            </div>
        </section>
        
        <Footer />
        <div v-if="hostMode" class="sticky bottom-0">
            <div class="bg-white h-20 outline outline-1 outline-neutral-300">
                <div class="m-auto lg:w-5/6 xl:w-11/12 2xl:w-7/12 flex items-center h-full justify-between">
                    <small>You are currently in host mode.</small>
                    <div v-if="selectedHostTour" class="flex gap-2">
                        <h3>Selected:</h3>
                        <p>{{ selectedHostTour.name }}</p>
                    </div>
                    <h3 v-else>Please select a tour</h3>
                    <div class="flex gap-2 pr-6">
                        <SecondaryButton
                            :icon="false"
                            @click="$inertia.get(route('host.index'))"
                        >Back</SecondaryButton>
                        <Button 
                            :disabled="!selectedHostTour"
                            @click="router.get(route('group-tour.index', { id: selectedHostTour.id }))"
                        >Proceed</Button>
                    </div>
                </div>
            </div>
        </div>
    </MarginLayout>
</template>