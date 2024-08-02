<script setup>
import MarginLayout from '@/Layouts/MarginLayout.vue';
import Checkbox from '@/Components/Checkbox.vue';
import { useFormatText } from '@/Composables/formatText';

// PrimeVue 
import Steps from 'primevue/steps';
import Avatar from 'primevue/avatar';
import Badge from 'primevue/badge';
import Button from 'primevue/button';
import IconField from 'primevue/iconfield';
import InputText from 'primevue/inputtext';
import InputIcon from 'primevue/inputicon';
import Calendar from 'primevue/calendar';
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import Fieldset from 'primevue/fieldset';
import Slider from 'primevue/slider';
import Textarea from 'primevue/textarea';
import { ref, computed } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import dayjs from 'dayjs';

const props = defineProps({
    destinations: {
        type: [Array, Object],
    },
    activities: {
        type: [Array, Object],
    },
    guide_types: {
        type: [Array, Object],
    },
    lodge_types: {
        type: [Array, Object],
    },
    travel_intensities: {
        type: [Array, Object],
    },
    age_range: {
        type: [Array, Object],
    },
});

const active = ref(0);
const budget = ref([0, 5000]);
const duration = ref([2, 30]);
const restriction = ref(false);
const filterDestination = ref(null);
const isFlexible = ref(false);

const useSelection = () => {
    const selectedItems = ref([]);

    const selectItem = (item) => {
        const index = selectedItems.value.findIndex(selectedItem => selectedItem === item);
        if (index != -1) selectedItems.value.splice(index, 1);
        else selectedItems.value[0] = item;
    };

    const selectDate = (item) => {
        const index = selectedItems.value.findIndex(selectedItem => selectedItem === item);
        if (index != -1) { 
            selectedItems.value.splice(index, 1);
        } else {
            selectedItems.value[0] = dayjs(item).format('YYYY-MM-DD');
        }
    };

    const selectItems = (item) => {
        const index = selectedItems.value.findIndex(selectedItem => selectedItem === item);
        if (index != -1) selectedItems.value.splice(index, 1);
        else selectedItems.value.push(item);
    };

    const sliderItem = (item, column) => {
        const sliderObject = { key: column, item: item.value };

        const index = selectedItems.value.findIndex(selectedItem => selectedItem['key'] === column);
        if ((item.value[0] == 0 && item.value[1] == 5000) && column === 'budget') selectedItems.value.splice(index, 1);
        else if ((item.value[0] == 2 && item.value[1] == 30) && column === 'duration') selectedItems.value.splice(index, 1);
        else if (index != -1) selectedItems.value[index] = sliderObject; 
        else selectedItems.value.push(sliderObject);    
    };

    return { selectedItems, selectItem, selectDate, selectItems, sliderItem };
};

const selectedDestination = useSelection();
const selectedGuide = useSelection();
const selectedIntensity = useSelection();
const selectedActivity = useSelection();
const selectedLodging = useSelection();
const selectedAge = useSelection();
const selectedBudget = useSelection();
const selectedDate = useSelection();
const selectedDuration = useSelection();

const useTripDetails = (selections) => {
    return {
        overallDetails: computed(() => {
            const overallDetails = {};
            for (const [key, selectedItems] of Object.entries(selections)) {
                if (selectedItems.length != 0) overallDetails[key] = selectedItems;
            }
            return overallDetails;
        })
    };
};

const tripDetails = useTripDetails({
    destination: selectedDestination.selectedItems.value,
    guide: selectedGuide.selectedItems.value,
    intensity: selectedIntensity.selectedItems.value,
    activity: selectedActivity.selectedItems.value,
    lodging: selectedLodging.selectedItems.value,
    age: selectedAge.selectedItems.value,
    budget: selectedBudget.selectedItems.value,
    date: selectedDate.selectedItems.value,
    duration: selectedDuration.selectedItems.value,
});

const searchDestination = computed(() => {
    if (!filterDestination.value) return props.destinations;
    else return props.destinations.filter(destination => destination.includes(filterDestination.value));
});

const isDestinationSelected = destination => selectedDestination.selectedItems.value.some(dest => dest === destination);
const isActivitySelected = activity => selectedActivity.selectedItems.value.some(act => act === activity);
const isLodgeSelected = lodging => selectedLodging.selectedItems.value.some(lodge => lodge === lodging);

const setFlexibleDate = (status) => { 
    isFlexible.value = !!status;
    selectedDate.selectedItems.value = [];
};

const minDate = () => new Date();

const items = ref([
    {
        label: 'Start',
        icon: 'pi pi-user'
    },
    {
        label: 'Where'
    },
    {
        label: 'How'
    },
    {
        label: 'What'
    },
    {
        label: 'Who'
    },
    {
        label: 'When'
    },
    {
        label: 'Your Trip',
        icon: 'pi pi-check'
    }
]);

const headers  = [
    {
        title: "Hello! Let's brief you on how this all work."
    },
    {
        title: "Where do you want to go?",
        subtitle: "Select the places you'd like to visit on this trip."
    },
    {
        title: "How do you want to travel?",
        subtitle: "Select your preferred travel and level of intensity."
    },
    {
        title: "What should your trip focus on and lodging be?",
        subtitle: "Select your preferred activity and lodging."
    },
    {
        title: "Who is traveling?",
        subtitle: "Select the ages and budgets of all your travel companions."
    },
    {
        title: "When would you like to start your trip?",
        subtitle: "Select the start date and duration of your trip."
    },
    {
        title: "Your Trip",
        subtitle: "Please confirm your trip preferences."
    }
];

const guidelines = [
    {
        title: "Share Your Preferences",
        paragraph: "Let us know your dream travel experience by answering a few simple questions."
    },
    {
        title: "Let Us Craft Your Experience",
        paragraph: "We have an array of options to tailor-make your ideal itinerary, drawing from a diverse selection of travel possibilities."
    },
    {
        title: "Handpick Your Unique Adventure",
        paragraph: "Choose from the personalized recommendations we provide, ensuring your journey is precisely what you've been dreaming of."
    }
];

const restrictions = [
    {
        title: "Activity and Attractions",
        paragraph: "Certain attractions or activities may have age restrictions or recommendations. Knowing your age helps the agency tailor the itinerary to suit your preferences and ensure that you can participate in all planned activities."
    },
    {
        title: "Legal and Safety Requirements",
        paragraph: "Age may be a factor in determining eligibility for certain tours or activities due to safety concerns or legal regulations."
    },
    {
        title: "Pricing",
        paragraph: "Some services like flights, accommodations, and activities might have different prices based on age. For instance, children or seniors may be eligible for discounts."
    }
];

const form = useForm({
    destinations: computed(() => tripDetails.overallDetails.value.destination ?? []),
    activities: computed(() => tripDetails.overallDetails.value.activity ?? []),
    guide_type: computed(() => tripDetails.overallDetails.value.guide?.[0] ?? null),
    lodge_type: computed(() => tripDetails.overallDetails.value.lodging?.[0] ?? null),
    start_date: computed(() => tripDetails.overallDetails.value.date?.[0] ?? null),
    travel_intensity: computed(() => tripDetails.overallDetails.value.intensity?.[0] ?? null),
    age_range: computed(() => tripDetails.overallDetails.value.age ?? []),
    min_budget: computed(() => tripDetails.overallDetails.value.budget?.[0]?.item?.[0] ?? null),
    max_budget: computed(() => tripDetails.overallDetails.value.budget?.[0]?.item?.[1] ?? null),
    min_duration: computed(() => tripDetails.overallDetails.value.duration?.[0]?.item?.[0] ?? null),
    max_duration: computed(() => tripDetails.overallDetails.value.duration?.[0]?.item?.[1] ?? null),
    // note: null,
});

const submitForm = () => {
    form.post(route('customize.store'), {
        onSuccess: () => form.reset(),
        onError: (error) => console.error('Error:', error),
        onFinish: () => console.log('Finish:', tripDetails.overallDetails.value),
    });
};


</script>

<template>
    <MarginLayout>
        <div id="stepper" class="bg-white h-fit shadow py-6">
            <div class="w-11/12 lg:max-w-full lg:mx-16 flex items-center relative mx-auto">
                <ApplicationLogo />
                <Steps 
                    v-model:activeStep="active" 
                    :model="items" 
                    :readonly="false" 
                    class="text-xs mx-auto grow lg:max-w-5xl" 
                />
            </div>
        </div>
        <section class="my-8 mx-auto max-w-2xl lg:flex xl:flex xl:max-w-7xl">
            <Avatar icon="pi pi-user" class="mb-6 ml-1 xl:mt-3 xl:ml-0 bg-white" size="large" shape="circle" />
            <div class="grow">
                <div class="mx-auto
                    max-w-11/12
                    lg:max-w-lg
                    xl:max-w-4xl
                    bg-white shadow rounded py-6 px-5 relative"
                >
                    <div class="flex items-center">
                        <div class="bg-white rotate-45 size-4 absolute top-[-6px] xl:left-[-6px] xl:top-6"></div>
                        <h1>{{ headers[active].title }}</h1>
                    </div>
                    <p class="text-lg mb-8">
                        {{ headers[active].subtitle }}
                    </p>
                    <div v-if="active == 0">
                        <div v-for="(guideline, index) in guidelines" :key="index" class="mt-6 flex items-center gap-4">
                            <Badge :value="index + 1" class="bg-primary place-self-start" />
                            <div>
                                <h3>{{ guideline.title }}</h3>
                                <p>{{ guideline.paragraph }}</p>
                            </div>
                        </div>  
                        <div class="flex flex-wrap justify-center mt-8">
                            <Button label="Get Started" @click="active++" />
                        </div>
                    </div>

                    <!-- Destination -->
                    <div v-if="active == 1">
                        <IconField iconPosition="left" class="mb-4">
                            <InputIcon class="pi pi-search" />
                            <InputText 
                                placeholder="Search" 
                                class="flex w-full"
                                v-model="filterDestination"
                            />
                        </IconField>
                        <p class="text-gray-500">Places in Malaysia</p>
                        <div class="grid lg:grid-cols-4 gap-4">
                            <div 
                                v-for="destination in searchDestination" 
                                class="bg-gray-200 rounded-lg relative hover:shadow"
                                @click="selectedDestination.selectItems(destination.name)"
                            >
                                <img :src="destination.image" class="aspect-video">
                                <div class="flex flex-col">
                                    <span v-if="isDestinationSelected(destination.name)" class="absolute top-0 right-0 p-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                                            <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-1.814a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                    <span class="absolute bottom-0 right-0 px-4 pb-2 text-right">{{ destination.name }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Guide & Intensity -->
                    <div v-if="active == 2">
                        <TabView>
                            <TabPanel header="Travel Guide">
                                <Fieldset 
                                    v-for="guide in guide_types"
                                    :legend="useFormatText(guide.type)"
                                    class="mb-4 cursor-pointer"
                                    :class="{'border-primary': selectedGuide.selectedItems.value.includes(guide.type)}"
                                    @click="selectedGuide.selectItem(guide.type)"
                                >
                                    <p>{{ guide.description }}</p>
                                </Fieldset>
                            </TabPanel>
                            <TabPanel header="Travel Intensity">
                                <Fieldset
                                    v-for="travel in travel_intensities"
                                    :legend="useFormatText(travel.intensity)"
                                    class="mb-4 cursor-pointer"
                                    :class="{'border-primary': selectedIntensity.selectedItems.value.includes(travel.intensity)}"
                                    @click="selectedIntensity.selectItem(travel.intensity)"
                                >
                                    <p>{{ travel.description }}</p>
                                </Fieldset>
                            </TabPanel>
                        </TabView>
                    </div>

                    <!-- Activity & Lodge -->
                    <div v-if="active == 3">
                        <TabView>
                            <TabPanel header="Activity">
                                <div class="grid lg:grid-cols-4 gap-4">
                                    <div 
                                        v-for="activity in activities" 
                                        class="bg-gray-200 rounded-lg relative hover:shadow"
                                        @click="selectedActivity.selectItems(activity.name)"
                                    >
                                        <img :src="activity.image" class="aspect-video">
                                        <div class="flex flex-col">
                                            <span v-if="isActivitySelected(activity.name)" class="absolute top-0 right-0 p-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                                                    <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-1.814a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                            <span class="absolute bottom-0 right-0 px-4 pb-2">{{ activity.name }}</span>
                                        </div>
                                    </div>
                                </div>
                            </TabPanel>
                            <TabPanel header="Lodge">
                                <div class="grid lg:grid-cols-4 gap-4">
                                    <div
                                        v-for="lodge in lodge_types"
                                        class="bg-gray-200 rounded-lg relative hover:shadow"
                                        @click="selectedLodging.selectItem(lodge.type)"
                                    >
                                        <img src="" class="aspect-video">
                                        <div class="flex flex-col">
                                            <span v-if="isLodgeSelected(lodge.type)" class="absolute top-0 right-0 p-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                                                    <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-1.814a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                            <span class="absolute bottom-0 right-0 px-4 pb-2">{{ useFormatText(lodge.type) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </TabPanel>
                        </TabView>
                    </div>

                    <!-- Age & Budget -->
                    <div v-if="active == 4">
                        <TabView>
                            <TabPanel header="Age">
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mt-2">
                                    <div v-for="(age, index) in age_range" :key="index" class="bg-gray-200 rounded">
                                        <Button 
                                            :label="age.range" 
                                            :class="['w-full' ,{'border-primary bg-primary-100 text-black': selectedAge.selectedItems.value.includes(age.range)}]" 
                                            outlined severity="secondary" 
                                            @click="selectedAge.selectItems(age.range)"
                                        />
                                    </div>
                                    <small @click="restriction = !restriction" class="underline text-gray-500 cursor-pointer items-center justify-center flex">Age Guideline</small>
                                </div>
                            </TabPanel>
                            <TabPanel header="Budget">
                                <p>Use the slider to set the minimum and maximum costs per traveler.</p>
                                <div class="max-w-md lg:max-w-2xl mt-8 m-auto">
                                    <div class="flex justify-between mb-4">
                                        <span><strong>min.</strong> RM{{ budget[0] }}</span>
                                        <span><strong>max.</strong> RM{{ budget[1] }}</span>
                                    </div>
                                    <Slider
                                        :min="0"
                                        :max="5000"
                                        v-model="budget"
                                        range
                                        @slideend="(event) => selectedBudget.sliderItem(event, 'budget')"
                                    >
                                    </Slider>
                                </div>
                            </TabPanel>
                        </TabView>
                    </div>

                    <!-- Start Date & Duration -->
                    <div v-if="active == 5">
                        <TabView>
                            <TabPanel header="Start Date">
                                <p class="mb-4">Not sure yet? Choose 'I'm flexible'.</p>
                                <div class="grid grid-cols-1 xl:grid-cols-2 w-full">
                                    <div class="mb-8">
                                        <Checkbox
                                            label="I'm flexible"
                                            value="flexible"
                                            class="pl-2"
                                            @update:checked="setFlexibleDate"
                                        />
                                    </div>
                                    <div>
                                        <p class="mb-4">Or select a specific date</p>
                                        <Calendar
                                            :selectOtherMonths="true"
                                            :minDate="minDate()"
                                            inline :disabled="isFlexible"
                                            @date-select="selectedDate.selectDate"
                                        />
                                    </div>
                                </div>
                                <!-- <div class="mb-8">
                                    <p class="mb-4">Or select the months in which you want to start your trip:</p>
                                    <div class="grid grid-cols-2 gap-y-2 pl-2">
                                        <Checkbox
                                            v-for="(month, index) in getMonths()"
                                            :key="index"
                                            :label="month"
                                            :value="month"
                                            :disabled="isFlexible"
                                            @update:checked="selectedDate.selectItems(month)"
                                        />
                                    </div>
                                </div> -->
                            </TabPanel>
                            <TabPanel header="Duration">
                                <p>Use the slider to set the length of your trip.</p>
                                <div class="max-w-md lg:max-w-2xl mt-8 m-auto">
                                    <div class="flex justify-between mb-4">
                                        <span><strong>min.</strong> {{ duration[0] }} Days</span>
                                        <span><strong>max.</strong> {{ duration[1] }} Days</span>
                                    </div>
                                    <Slider
                                        :min="2"
                                        :max="30"
                                        v-model="duration"
                                        range
                                        @slideend="(event) => selectedDuration.sliderItem(event, 'duration')"
                                    >
                                    </Slider>
                                </div>
                            </TabPanel>
                        </TabView>
                    </div>

                    <!-- Final -->
                    <div v-if="active == 6" class="flex flex-col gap-3">
                        <div v-for="(categoryValue, category) in tripDetails.overallDetails.value">
                            <h4 class="capitalize">{{ category }}</h4>
                            <div v-if="category === 'budget'">
                                <h3 v-for="value in categoryValue" class="font-semibold text-black">
                                    RM{{ value.item.join(' - RM') }}
                                </h3>
                            </div>  
                            <div v-else-if="category === 'duration'">
                                <h3 v-for="value in categoryValue" class="font-semibold text-black">
                                    {{ value.item.join(' - ') }} Days
                                </h3>
                            </div>
                            <div v-else-if="category === 'date'">
                                <h3 v-for="value in categoryValue" class="font-semibold text-black">
                                    {{ dayjs(value).format('DD MMMM YYYY') }}
                                </h3>
                            </div>
                            <div v-else>
                                <div class="flex gap-6">
                                    <h3 v-for="value in categoryValue" class="font-semibold text-black">{{ useFormatText(value) }}</h3>
                                </div>
                            </div>
                        </div>
                        <Textarea
                            v-if="Object.keys(tripDetails.overallDetails.value).length != 0"
                        />
                        <Button
                            label="Confirm"
                            :disabled="Object.keys(tripDetails.overallDetails.value).length == 0"
                            @click="submitForm"
                        />
                    </div>

                    <!-- Buttons -->
                    <div v-if="active != 0 && active != 6" class="flex flex-wrap justify-between mt-8">
                        <Button 
                            label="Previous" 
                            severity="secondary"
                            outlined
                            @click="active--" 
                        />
                        <Button 
                            label="Next" 
                            @click="active++" 
                            class="bg-primary"
                        />
                    </div>
                </div>  

                <!-- Age Restriction Details -->
                <div v-if="active == 4 && restriction" class="m-auto
                    max-w-sm
                    lg:max-w-4xl
                    bg-white shadow rounded py-6 px-5 mt-6 relative"
                >
                    <div class="flex items-center">
                        <div class="bg-white rotate-45 size-4 absolute left-[-6px]"></div>
                        <h1>Age Guideline</h1>
                    </div> 
                    <div v-for="(restriction, index) in restrictions" :key="index" class="mt-6 flex items-center gap-4">
                        <Badge :value="index + 1" class="bg-primary place-self-start" />
                        <div>
                            <h3>{{ restriction.title }}</h3>
                            <p>{{ restriction.paragraph }}</p>
                        </div>
                    </div>  
                </div>
            </div>
            <div v-if="active != 6" class="bg-white rounded shadow min-w-72 max-w-72 h-fit py-6 px-4 hidden xl:block">
                <h2 class="mb-4">My Trip</h2>
                <div v-if="Object.keys(tripDetails.overallDetails.value).length != 0" class="flex flex-col gap-4">
                    <div 
                        v-for="(categoryValue, category) in tripDetails.overallDetails.value"
                        class="grid grid-cols-2"
                    >
                        <h5 class="capitalize">{{ category }}</h5>
                        <div v-if="category === 'budget'" class="flex gap-2">
                            <i class="pi pi-check-circle text-success"></i>
                            <h5 v-for="value in categoryValue" class="font-semibold text-black">
                                RM{{ value.item.join(' - RM') }}
                            </h5>
                        </div>  
                        <div v-else-if="category === 'duration'" class="flex gap-2">
                            <i class="pi pi-check-circle text-success"></i>
                            <h5 v-for="value in categoryValue" class="font-semibold text-black">
                                {{ value.item.join(' - ') }} Days
                            </h5>
                        </div>
                        <div v-else-if="category === 'date'" class="flex gap-2">
                            <i class="pi pi-check-circle text-success"></i>
                            <h5 v-for="value in categoryValue" class="font-semibold text-black">
                                {{ dayjs(value).format('DD MMMM YYYY') }}
                            </h5>
                        </div>
                        <div v-else>
                            <div v-for="value in categoryValue" class="flex items-start gap-2">
                                <i class="pi pi-check-circle text-success"></i>
                                <h5 class="font-semibold text-black">{{ useFormatText(value) }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <small v-else>Your selection will be shown here.</small>
            </div>
        </section>
    </MarginLayout>
</template>