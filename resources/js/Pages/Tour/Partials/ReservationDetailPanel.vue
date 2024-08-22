<script setup>
import { DEFAULT_DATE_FORMAT } from '@/constant';
import { useFormatPrice } from '@/Composables/formatPrice';

import Card from '@/Components/Card.vue';

import Button from 'primevue/button';
import Divider from 'primevue/divider';
import InlineMessage from 'primevue/inlinemessage';
import Tag from 'primevue/tag';

import { computed, watch } from 'vue';
import dayjs from 'dayjs';

const emit = defineEmits(['update-amount', 'reset-child', 'submit-reservation']);

const props = defineProps({
    tour: {
        type: Object,
        required: true
    },
    form: {
        type: Object,
        required: true,
    }
});

const hasDiscount = computed(() => props.tour.discount != null);

const formattedDate = computed(() => dayjs(props.form.preferred_date).format(DEFAULT_DATE_FORMAT));

const getNumberOfTraveller = computed(() => props.form.adult + props.form.child);

const getSelectedPackage = computed(() => props.tour.packages.find(item => item.id === props.form.package_id));
const parseSelectedPackagePrice = computed(() => getSelectedPackage.value?.price);
const getSelectedPackagePrice = computed(() => useFormatPrice(parseSelectedPackagePrice));

const getSelectedPickupLocation = computed(() => props.tour.pickup_location.find(item => item.id === props.form.pickup_location_id));

const calculateTotalPrice = computed(() => parseSelectedPackagePrice.value * getNumberOfTraveller.value);
const getTotalPrice = computed(() => useFormatPrice(calculateTotalPrice));

const calculateDiscountedPrice = computed(() => calculateTotalPrice.value * (props.tour.discount.percentage / 100));
const calculateDiscount = computed(() => calculateTotalPrice.value - calculateDiscountedPrice.value);

const getDiscount = computed(() => useFormatPrice(calculateDiscountedPrice.value, 2, false, '-'));
const getTotalPriceAfterDiscount = computed(() => useFormatPrice(calculateDiscount.value, 2, false));

const isPackageSelected = computed(() => props.form.package_id != null);
const isDateSelected = computed(() => props.form.preferred_date != null);
const isPickupLocationSelected = computed(() => props.form.pickup_location_id != null);
const isNumberOfTravellerFilled = computed(() => getNumberOfTraveller.value > 0);
const isNecessaryFieldsFilled = computed(() => isNumberOfTravellerFilled.value && isPackageSelected.value);
const getFinalPrice = computed(() => hasDiscount ? calculateDiscount.value : getTotalPrice.value);

const isFormValid = computed(() => {
    const totalTravellers = props.form.adult + props.form.child;

    return (
        props.form.package_id != null &&
        props.form.contact_method_id != null &&
        props.form.pickup_location_id != null &&
        props.form.preferred_date != null &&
        props.form.first_name &&
        props.form.last_name &&
        props.form.email &&
        props.form.phone_number != null &&
        (totalTravellers >= props.tour.min_pax)
    );
});

watch(() => props.form.adult, (newValue) => {
    if (newValue < 1) {
        emit('reset-child');
    }
});

watch(calculateTotalPrice, (newValue) => {
    emit('update-amount', newValue);
});

</script>

<template>

    <Card>
        <template #image>
            <img
                src="https://static.travelstride.com/store/51/ad4f08f81843578f3ea235b037593e/b669206bb8425f9409b22d1a87c8d181.jpg"
                class="rounded-t-md"
            >
        </template>
        <div class="flex flex-col gap-2">
            <div>
                <h2>{{ tour.name }}</h2>
                <p>{{ tour.duration }} {{ tour.duration > 1 ? 'Days' : 'Day' }}</p>
            </div>
            <div>
                <h2>Reserved Date</h2>
                <p>{{ isDateSelected ? formattedDate : 'Not selected' }}</p>
            </div>
            <div v-if="isPackageSelected">
                <h2>{{ getSelectedPackage.name }}</h2>
                <p>
                    {{ useFormatPrice(getSelectedPackage.price) }}
                </p>
            </div>
            <div>
                <h2>Pick-up Location</h2>
                <p>{{ isPickupLocationSelected ? getSelectedPickupLocation.location : 'Not selected' }}</p>
            </div>
        </div>
    </Card>

    <Card>
        <h1>Package Details</h1>
        <div v-if="isPackageSelected" class="flex flex-wrap gap-3">
            <Tag 
                v-for="activity in getSelectedPackage.activities" 
                :value="activity.name"
            />
        </div>
        <div v-else>
            <InlineMessage class="justify-start w-full" severity="secondary"> 
                <span>Please select a package.</span>
            </InlineMessage>
        </div>
    </Card>

    <Card>
        <h1>Price Breakdown</h1>
        <InlineMessage 
            v-if="!isNecessaryFieldsFilled" 
            class="justify-start w-full" 
            severity="secondary"
            pt:icon:root="flex align-start"
        >
            <div>
                <span>Please fill in the</span>
                <span v-if="!isNumberOfTravellerFilled"> number of travellers</span>
                <span v-if="!isNumberOfTravellerFilled && !isPackageSelected"> and </span> 
                <span v-if="!isPackageSelected"> package type</span>.
            </div>
        </InlineMessage>
        <div v-else class="flex flex-col">
            <div class="flex gap-2 justify-between">
                <span>{{ getSelectedPackage.name }}</span>
                <span>{{ getSelectedPackagePrice }}</span>
            </div>
            <div class="flex justify-between mb-4">
                <small>
                    {{ getNumberOfTraveller }}
                    Travellers x
                    {{ getSelectedPackagePrice }}
                </small>
                <small>{{ getTotalPrice }}</small>
            </div>

            <div v-if="hasDiscount" class="flex justify-between">
                <div class="flex flex-col">
                    <span>
                        {{ tour.discount.percentage }}% Discount
                    </span>
                    <small>{{ tour.discount.type }}</small>
                </div>
                <span class="text-error">{{ getDiscount }}</span>
            </div>
            <Divider />
            <div class="flex justify-between">
                <h3>Total Due</h3>
                <h3>{{ getTotalPrice }}</h3>
            </div>
        </div>
    </Card>
    <Button
        label="Continue"
        class="w-full justify-center" rounded
        :disabled="!isFormValid || form.processing"
        @click="emit('submit-reservation')"
    />

</template>