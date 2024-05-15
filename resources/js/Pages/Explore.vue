<script setup>
import NavBar from '@/Components/NavBar.vue';
import CheckBoxFilter from '@/Components/CheckBoxFilter.vue';
import DropdownFilter from '@/Components/DropdownFilter.vue';
import HorizontalCard from '@/Components/HorizontalCard.vue';
import InputTextFilter from '@/Components/InputTextFilter.vue';
import CompareBox from '@/Components/CompareBox/CompareBox.vue';
import MarginLayout from '@/Layouts/MarginLayout.vue';
import AgeFilter from '@/Components/AgeFilter.vue';

// PrimeVue 
import Slider from 'primevue/slider';
import Toast from 'primevue/toast';
import { useToast } from 'primevue/usetoast';


import { computed, ref } from 'vue';

const props = defineProps({
    tours: {
        type: Array
    }
})

const items = ref([]);
const currentFilter = ref([]);
const range = ref([1, 20]);
const toast = useToast();

const showToast = () => {
    toast.add({ severity: 'warn', summary: 'Limit Reached', detail: 'You have reached the limit of 3 tours.', life: 3000 })
}

// Emit event for HorizontalCard Component 
const addCompareTour = (tour) => {
    const index = items.value.findIndex(item => item.id === tour.id);
    if (index != -1) items.value.splice(index, 1);
    else if (items.value.length >= 2) showToast(); 
    else items.value.push(tour);
}

// Emit event for CompareBox Component
const removeCompareTour = (tour) => {
    const index = items.value.findIndex(item => item.id === tour.id);
    if (index != -1) items.value.splice(index, 1);
    else console.log("Tour ID not found!");
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
    value: ['Easy', 'Medium', 'Hard']   
};

const formatReadable = (text) => {
    return text.split('_')
            .map(part => part.charAt(0).toUpperCase() + part.slice(1))
            .join(' ')
}

</script>

<template>
    <MarginLayout>
        <NavBar />
        <Toast />
        <CompareBox 
            :items="items"
            @remove-compare-tour="removeCompareTour"
        />
        <div id="heading-container" class="bg-gradient-to-r from-primary-200 to-primary-400 font-sans py-4 shadow border">
            <div id="heading" class="m-auto lg:w-5/6 xl:w-10/12 2xl:w-7/12">
                <h1 class="text-2xl font-bold">Explore Malaysia</h1>
            </div>
        </div>
        <div id="content-container" class="grid lg:grid-cols-8 h-screen lg:w-5/6 xl:w-10/12 2xl:w-7/12 m-auto gap-6 my-4">
            <div id="filter" class="col-span-2 lg:block hidden">
                <div id="applied" class="bg-primary-100 h-fit mb-4 rounded-md p-4 shadow">
                    <h3 class="font-bold text-xl">Applied Filters</h3>
                    <div v-if="currentFilter.length != 0" class="mt-4 gap-1 flex flex-col">
                        <div v-for="filter in currentFilter" class="flex justify-between">
                            <span v-if="filter.column == 'day'">
                                Duration is {{ filter.filter.value[0] }} - {{ filter.filter.value[1] }} days
                            </span>
                            <span v-else>
                                {{ formatReadable(filter.column) }} is {{ filter.filter }}
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
                    <DropdownFilter label="Age">
                        <AgeFilter 
                            column="age"
                            @filter="handleFilter"
                        />
                    </DropdownFilter>
                </div>
            </div>
            <div id="content" class="col-span-6">
                <div id="column" class="h-fit">
                    <div id="box">
                        <HorizontalCard
                            v-for="tour in filteredTours"
                            :key="tour.id"
                            :tour="tour" 
                            :items="items"
                            @compare-tour="addCompareTour"
                        />
                    </div>
                </div>
            </div>
        </div>
    </MarginLayout>
</template>