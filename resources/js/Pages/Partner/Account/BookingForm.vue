<script setup>
import CustomSectionCard from '@/Components/CustomSectionCard.vue';
import TextInput from '@/Components/TextInput.vue';
import Button from 'primevue/button';
import Calendar from 'primevue/calendar';
import Dropdown from 'primevue/dropdown';
import InputNumber from 'primevue/inputnumber';
import Panel from 'primevue/panel';
import { useForm } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import dayjs from 'dayjs';

const props = defineProps({
    partner: {
        type: Object,
        required: true
    },
    tours: {
        type: Object,
        required: true
    },
});

const selectedTour = ref(null);

const form = useForm({
    package_id: null,
    contact_method_id: props.partner.settings?.contact_method_id,
    departure_date: null,
    finished_date: null,
    adult: 0,
    child: 0,
    note: null,
});

const packages = computed(() => selectedTour.value?.packages);

const minDate = computed(() => {
    let today = new Date();
    let month = today.getMonth();
    let year = today.getFullYear();
    let date = today.getDate();
    return new Date(year, month, date);
});

const requiredFieldsFilled = computed(() => {
    return (
        selectedTour.value != null &&
        form.package_id != null &&
        form.departure_date != null &&
        form.finished_date != null &&
        getNumberOfTraveller() >= selectedTour.value?.min_pax
    )
});

function getNumberOfTraveller() {
    return form.adult + form.child;
}

function setFinishedDate(date) {
    form.finished_date = dayjs(date).add(selectedTour.value?.duration, 'day').toDate();
};

const proceed = () => {
    form.post(route('partner-account-booking.store'), {
        onError: (error) => console.error(error),
    });
};

</script>

<template>

    <CustomSectionCard index="1" title="Tour Selection">
        <Dropdown
            v-model="selectedTour"
            :options="tours"
            optionLabel="name"
            placeholder="Select a tour"
            @change="form.reset()"
        />
    </CustomSectionCard>

    <CustomSectionCard index="2" title="Date Selection">
        <Calendar
            v-model="form.departure_date"
            :minDate="minDate"
            showIcon
            iconDisplay="input"
            placeholder="Select departure date"
            :disabled="!selectedTour"
            @date-select="setFinishedDate"
        />
    </CustomSectionCard>

    <CustomSectionCard index="3" title="Package Selection">
        <Dropdown
            v-model="form.package_id"
            :options="packages"
            optionLabel="name"
            optionValue="id"
            placeholder="Select a package"
            :disabled="!selectedTour"
        />
    </CustomSectionCard>
    
    <CustomSectionCard index="4" title="Number of Travellers">
        <template #subtitle>
            <p v-if="selectedTour">Please select a minimum of {{ selectedTour?.min_pax }} travellers.</p>
        </template>
        <div class="bg-white relative px-6 py-3 flex items-center justify-between rounded-md outline outline-1 outline-neutral-300">
            <h2>Adult</h2>
            <InputNumber
                v-model="form.adult"
                showButtons buttonLayout="horizontal"
                :min="0" :max="99" placeholder="0"
                :invalid="form.errors.adult != null ? true : false"
                :disabled="!selectedTour"
            >
                <template #incrementbuttonicon>
                    <span class="pi pi-plus"></span>
                </template>
                <template #decrementbuttonicon>
                    <span class="pi pi-minus"></span>
                </template>
            </InputNumber>
        </div>
        <div class="bg-white relative px-6 py-3 flex items-center justify-between rounded-md outline outline-1 outline-neutral-300">
            <div>
                <h2>Child</h2>
                <small>Age of under 18</small>
            </div>
            <InputNumber
                v-model="form.child"
                showButtons buttonLayout="horizontal"
                :min="0" :max="99" placeholder="0"
                :invalid="form.errors.child != null ? true : false"
                :disabled="!selectedTour || form.adult < 1"
            >
                <template #incrementbuttonicon>
                    <span class="pi pi-plus"></span>
                </template>
                <template #decrementbuttonicon>
                    <span class="pi pi-minus"></span>
                </template>
            </InputNumber>
        </div>
    </CustomSectionCard>

    <CustomSectionCard index="5" title="Note">
        <TextInput 
            v-model="form.note" 
            type="textarea"
            label="Special Requests / Questions" 
            :disabled="!selectedTour"
        />
    </CustomSectionCard>

    <!-- <div class="flex gap-4">
        <Panel header="Details" class="flex-1">
            <div class="flex flex-col gap-3">
                <div v-for="detail in details"  class="">
                    <h3>{{ detail.label }}</h3>
                    <p>{{ detail.value }}</p>
                </div>
            </div>
        </Panel>
        <Panel header="Price"  class="flex-1">
            
        </Panel>
    </div> -->

    <Button
        label="Proceed with Payment"
        @click="proceed"
        :disabled="!requiredFieldsFilled"
    />

</template>