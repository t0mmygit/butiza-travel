<script setup>
import MarginLayout from '@/Layouts/MarginLayout.vue';
import Checkbox from '@/Components/Checkbox.vue';

// PrimeVue 
import Steps from 'primevue/steps';
import Avatar from 'primevue/avatar';
import Badge from 'primevue/badge';
import Button from 'primevue/button';
import IconField from 'primevue/iconfield';
import InputText from 'primevue/inputtext';
import InputIcon from 'primevue/inputicon';
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import Fieldset from 'primevue/fieldset';
import Slider from 'primevue/slider';
import { ref, computed } from 'vue';

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
        if (index == -1) selectedItems.value.push(item);
        else selectedItems.value.splice(index, 1);
    };

    const sliderItem = (item, column) => {
        const sliderObject = { key: column, item: item.value };

        const index = selectedItems.value.findIndex(selectedItem => selectedItem['key'] === column);
        if ((item.value[0] == 0 && item.value[1] == 5000) && column === 'budget') selectedItems.value.splice(index, 1);
        else if ((item.value[0] == 2 && item.value[1] == 30) && column === 'duration') selectedItems.value.splice(index, 1);
        else if (index != -1) selectedItems.value[index] = sliderObject; 
        else selectedItems.value.push(sliderObject);    
    };

    return { selectedItems, selectItem, sliderItem };
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
                // Insert the key and assign value into each respective items
                if (selectedItems.value.length != 0) overallDetails[key] = selectedItems;
            }
            return overallDetails;
        })
    }
}

const tripDetails = useTripDetails({
    destination: selectedDestination.selectedItems,
    guide: selectedGuide.selectedItems,
    intensity: selectedIntensity.selectedItems,
    activity: selectedActivity.selectedItems,
    lodging: selectedLodging.selectedItems,
    age: selectedAge.selectedItems,
    budget: selectedBudget.selectedItems,
    date: selectedDate.selectedItems,
    duration: selectedDuration.selectedItems
});

const searchDestination = computed(() => {
    if (!filterDestination.value) return destinations;
    else return destinations.filter(destination => destination.includes(filterDestination.value));
});

const isDestinationSelected = (destination) => selectedDestination.selectedItems.value.some(dest => dest === destination);
const isActivitySelected = (activity) => selectedActivity.selectedItems.value.some(act => act === activity);
const isLodgeSelected = (lodging) => selectedLodging.selectedItems.value.some(lodge => lodge === lodging);

// Get the next 8 months (MONTH YEAR) list of string
const getMonths = () => {
    var months = [];
    var currentDate = new Date();
    for (let i = 1; i <= 8; i++) {
        var nextMonth = new Date(currentDate.getFullYear(), currentDate.getMonth() + i, 1);
        months.push(nextMonth.toLocaleString('default', { month: 'long', year: 'numeric' }))
    } 
    return months;
};

const setFlexibleDate = (status) => isFlexible.value = !!status;

const months = getMonths();

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
]

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

const destinations = [
    "Kuala Lumpur",
    "Penang",
    "Langkawi",
    "Malacca (Melaka)",
    "Cameron Highlands",
    "Kota Kinabalu",
    "Kuching",
    "Perhentian Islands",
    "Tioman Island",
    "Taman Negara"
];

const preferences = [
    {
        guide: "Guided",
        description: "Follow the leader! Join a group led by a knowledgeable guide provided by the travel service, ensuring you don't miss any highlights."
    },
    {
        guide: "Private Guided",
        description: "Your exclusive adventure! Choose who joins you and enjoy the expertise of a dedicated guide provided by the travel service as you explore."
    },
    {
        guide: "Independent",
        description: "Your trip was planned from the get-go, but explore at your own pace without the guidance of a dedicated tour leader."
    }
];

const travels = [
    {
        intensity: "Relaxed",
        description: "Ideal for leisurely travelers, this level involves minimal physical exertion. Activities are gentle, such as short walks and easy sightseeing, catering to all ages and fitness levels."
    },
    {
        intensity: "Moderate",
        description: "Suited for active travelers, this level includes moderate physical activity like hiking and walking tours. Participants should have a reasonable level of fitness and be comfortable with walking on varied terrain."
    },
    {
        intensity: "Adventurous",
        description: "Tailored for thrill-seekers, this level offers challenging activities such as strenuous hikes and outdoor adventures. Participants need a high level of fitness and stamina to tackle demanding terrain and activities."
    }
];

const activities = [
  "City Exploration",
  "Outdoor Adventures",
  "Cultural Immersion",
  "Culinary Experiences",
  "Nature and Wildlife",
  "Relaxation and Wellness",
  "Adventure Sports",
  "Birding"
];

const lodging = [
    "Hotels",
    "Resorts",
    "Hostels",
    "Camping"
]

const ages = ["65+", "50-64", "36-49", "18-35", "12-17", "6-11", "5 and under"];

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

</script>

<template>
    <MarginLayout>
        <div id="stepper" class="bg-white h-fit shadow py-6">
            <div class="max-w-sm lg:max-w-full lg:mx-16 flex items-center relative">
                <SvgLogo />
                <Steps 
                    v-model:activeStep="active" 
                    :model="items" 
                    :readonly="false" 
                    class="text-xs mx-auto grow lg:max-w-5xl" 
                />
            </div>
        </div>
        <section class="my-8 mx-auto lg:flex xl:flex max-w-2xl xl:max-w-7xl">
            <Avatar icon="pi pi-user" class="mt-3 bg-white" size="large" shape="circle" />
            <div class="grow">
                <div class="mx-auto
                    max-w-sm
                    lg:max-w-lg
                    xl:max-w-4xl
                    bg-white shadow rounded py-6 px-5 relative"
                >
                    <div class="flex items-center">
                        <div class="bg-white rotate-45 size-4 absolute xl:left-[-6px]"></div>
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
                                @click="selectedDestination.selectItem(destination)"
                            >
                                <img src="" class="aspect-video">
                                <div class="flex flex-col">
                                    <span v-if="isDestinationSelected(destination)" class="absolute top-0 right-0 p-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                                            <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-1.814a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                    <span class="absolute bottom-0 right-0 px-4 pb-2">{{ destination }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Guide & Intensity -->
                    <div v-if="active == 2">
                        <TabView>
                            <TabPanel header="Travel Guide">
                                <Fieldset 
                                    v-for="preference in preferences"
                                    :legend="preference.guide"
                                    class="mb-4 cursor-pointer"
                                    :class="{'border-primary': selectedGuide.selectedItems.value.includes(preference.guide)}"
                                    @click="selectedGuide.selectItem(preference.guide)"
                                >
                                    <p>{{ preference.description }}</p>
                                </Fieldset>
                            </TabPanel>
                            <TabPanel header="Travel Intensity">
                                <Fieldset
                                    v-for="travel in travels"
                                    :key="travel.id"
                                    :legend="travel.intensity"
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
                                        @click="selectedActivity.selectItem(activity)"
                                    >
                                        <img src="" class="aspect-video">
                                        <div class="flex flex-col">
                                            <span v-if="isActivitySelected(activity)" class="absolute top-0 right-0 p-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                                                    <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-1.814a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                            <span class="absolute bottom-0 right-0 px-4 pb-2">{{ activity }}</span>
                                        </div>
                                    </div>
                                </div>
                            </TabPanel>
                            <TabPanel header="Lodge">
                                <div class="grid lg:grid-cols-4 gap-4">
                                    <div
                                        v-for="lodge in lodging"
                                        class="bg-gray-200 rounded-lg relative hover:shadow"
                                        @click="selectedLodging.selectItem(lodge)"
                                    >
                                        <img src="" class="aspect-video">
                                        <div class="flex flex-col">
                                            <span v-if="isLodgeSelected(lodge)" class="absolute top-0 right-0 p-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                                                    <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-1.814a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                            <span class="absolute bottom-0 right-0 px-4 pb-2">{{ lodge }}</span>
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
                                <div class="grid lg:grid-cols-4 gap-4 mt-2">
                                    <div v-for="(age, index) in ages" :key="index" class="bg-gray-200 rounded">
                                        <Button 
                                            :label="age" 
                                            :class="['w-full' ,{'border-primary bg-primary-100 text-black': selectedAge.selectedItems.value.includes(age)}]" 
                                            outlined severity="secondary" 
                                            @click="selectedAge.selectItem(age)"
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
                    <div v-if="active == 5">
                        <TabView>
                            <TabPanel header="Start Date">
                                <p class="mb-4">Not sure yet? Choose 'I'm flexible'.</p>
                                <div class="flex items-start">
                                    <Checkbox 
                                        label="I'm flexible" 
                                        value="flexible" 
                                        @update:checked="setFlexibleDate" 
                                    />
                                    <div class="m-auto">
                                        <p class="mb-4">Or select the months in which you want to start your trip:</p>
                                        <div class="grid grid-cols-2 gap-y-2 pl-2">
                                            <Checkbox
                                                v-for="(month, index) in months"
                                                :key="index"
                                                :label="month"
                                                :value="month"
                                                :disabled="isFlexible"
                                                @update:checked="selectedDate.selectItem(month)"
                                            />
                                        </div>
                                    </div>
                                </div>
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
                    <div v-if="active == 6">
                        <div v-for="(categoryValue, categoryName) in tripDetails.overallDetails.value" class="flex flex-col mb-3">
                            <h4 class="capitalize">{{ categoryName }}</h4>
                            <div v-if="categoryName != 'budget' && categoryName != 'duration'" class="flex gap-3">
                                <div v-for="value in categoryValue.value">
                                    <h4 class="font-semibold text-black">{{ value }}</h4>
                                </div>
                            </div>
                            <div v-else-if="categoryName == 'budget'" v-for="event in categoryValue.value" class="flex">
                                <h4 class="font-semibold text-black">RM{{ event.item[0] }} - RM{{ event.item[1] }}</h4>
                            </div>
                            <div v-else-if="categoryName == 'duration'" v-for="event in categoryValue.value">
                                <h4 class="font-semibold text-black">{{ event.item[0] }} - {{ event.item[1] }} Days</h4>
                            </div>
                        </div>
                        <Button label="Confirm" />
                    </div>
                    <!-- Button -->
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
                        v-for="(categoryValue, categoryName) in tripDetails.overallDetails.value"
                        class="grid grid-cols-2"
                    >
                        <h5 class="capitalize">{{ categoryName }}</h5>
                        <div v-if="categoryName != 'budget' && categoryName != 'duration'">
                            <div v-for="value in categoryValue.value">
                                <h5 class="font-semibold text-black">{{ value }}</h5>
                            </div>
                        </div>
                        <div v-else-if="categoryName == 'budget'" v-for="event in categoryValue.value">
                            <h5 class="font-semibold text-black">RM{{ event.item[0] }} - RM{{ event.item[1] }}</h5>
                        </div>
                        <div v-else-if="categoryName == 'duration'" v-for="event in categoryValue.value">
                            <h5 class="font-semibold text-black">{{ event.item[0] }} - {{ event.item[1] }} Days</h5>
                        </div>
                    </div>
                </div>
                <small v-else>Your selection will be shown here.</small>
            </div>
        </section>
    </MarginLayout>
</template>