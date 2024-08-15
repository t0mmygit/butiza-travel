<script setup>
import CustomSectionCard from '@/Components/CustomSectionCard.vue';
import Card from '@/Components/Card.vue';
import TextInput from '@/Components/TextInput.vue';
import PhoneInput from '@/Components/PhoneInput.vue';

import Button from 'primevue/button';
import InputNumber from 'primevue/inputnumber';
import InlineMessage from 'primevue/inlinemessage';
import Divider from 'primevue/divider';
import Tag from 'primevue/tag';
import Toast from 'primevue/toast';
import { useToast } from 'primevue/usetoast';

import { computed, watch } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';

import dayjs from 'dayjs';
import { useFormatText } from '@/Composables/formatText';
import { useFormatPrice } from '@/Composables/formatPrice';

const props = defineProps({
    tour: {
        type: Object,
        required: true
    },
    availability: {
        type: Object,
        required: true,
    },
    contact_methods: {
        type: Object,
        required: true
    },
    flash: {
        type: Object,
    }
});

const page = usePage();
const toast = useToast();

const contactIcons = [
    { call: 'pi pi-phone' },
    { email: 'pi pi-envelope' },
    { whatsapp: 'pi pi-whatsapp' }
];

const tourDetails = [
    {
        header: props.tour.name,
        info: `${props.tour.duration} Days`
    },
    {
        header: 'Departure Date',
        info: dayjs(props.availability.departure_date).format('DD MMMM YYYY'),
    },
    {
        header: 'Finished Date',
        info: dayjs(props.availability.finished_date).format('DD MMMM YYYY'),
    },
    {
        header: 'Guide Type',
        info: useFormatText(props.tour.guide_type),
    },  
];

const submit = () => {
    form.post(route('booking.store', { availability: props.availability.id}), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: (error) => {
            console.error(error);
            toast.add({ severity: 'error', summary: 'Error', detail: 'Please fill in all required fields correctly.', life: 3000 });
        },
    });
};

const form = useForm({
    package_id: null,
    contact_method_id: null,
    discount_id: props.tour.discount?.id ?? null,
    pickup_location_id: null,
    departure_date: props.availability.departure_date,
    finished_date: props.availability.finished_date,
    adult: null,
    child: null,
    note: null,
    first_name: page.props.auth.user?.first_name ?? null,
    last_name: page.props.auth.user?.last_name ?? null,
    email: page.props.auth.user?.email ?? null,
    phone_number: page.props.auth.user?.phone_number ?? null,
    amount: 0,
});

function getDiscount() {
    // TODO: get the discount
    return null;
}

const getContactMethodIcon = (iconName) => {
    const icon = contactIcons.find(icon => iconName.toLowerCase() in icon );
    return icon ? icon[iconName.toLowerCase()] : '';
};

const setPackageId = (packageId) => form.package_id = packageId;
const setPickupLocationId = (pickUpLocationId) => form.pickup_location_id = pickUpLocationId;
const setContactMethodId = (contactMethodId) => form.contact_method_id = contactMethodId;

// TODO: Discount Implementation
const hasDiscount = computed(() => false);

const getNumberOfTraveller = computed(() => form.adult + form.child);

const getSelectedPackage = computed(() => props.tour.packages.find(item => item.id === form.package_id));
const parseSelectedPackagePrice = computed(() => parseFloat(getSelectedPackage.value?.price));
const getSelectedPackagePrice = computed(() => useFormatPrice(parseSelectedPackagePrice));

const getTotalPrice = computed(() => useFormatPrice(calculateTotalPrice));
const calculateTotalPrice = computed(() => parseSelectedPackagePrice.value * getNumberOfTraveller.value);

const isPackageSelected = computed(() => form.package_id != null);
const isNumberOfTravellerFilled = computed(() => getNumberOfTraveller.value >= props.tour.min_pax);
const isNecessaryFieldsFilled = computed(() => isNumberOfTravellerFilled.value && isPackageSelected.value);

const isFormValid = computed(() => {
    const totalTravellers = form.adult + form.child;

    return (
        form.package_id != null &&
        form.pickup_location_id != null &&
        form.first_name &&
        form.last_name && 
        form.email &&
        form.phone_number != null &&
        form.contact_method_id != null &&
        (totalTravellers >= props.tour.min_pax)
    );
});

watch(calculateTotalPrice, (newValue) => {
    form.amount = newValue;
});

watch(() => form.adult, (newValue) => {
    if (newValue < 1) {
        form.reset('child');
    }
});

</script>

<template>
    <div class="flex w-full gap-6 my-6">
        <Toast />
        <div class="flex w-full">
            <div class="mx-auto grow max-w-full">
                <form>
                    <div class="flex flex-col gap-6">
                        <!-- Number of travellers -->
                        <CustomSectionCard :index="1" title="How many are travelling?" :error="form.errors.adult || form.errors.child">
                            <template #subtitle>
                                <p>Please select a minimum of {{ tour.min_pax }} travellers.</p>
                            </template>
                            <div class="bg-white relative px-6 py-3 flex items-center justify-between rounded-md outline outline-1 outline-surfaceBorder">
                                <h2>Adult</h2>
                                <InputNumber
                                    v-model="form.adult"
                                    showButtons buttonLayout="horizontal"
                                    :min="0" :max="99" placeholder="0"
                                    :invalid="form.errors.adult != null ? true : false"
                                >
                                    <template #incrementbuttonicon>
                                        <span class="pi pi-plus"></span>
                                    </template>
                                    <template #decrementbuttonicon>
                                        <span class="pi pi-minus"></span>
                                    </template>
                                </InputNumber>
                            </div>
                            <div class="bg-white relative px-6 py-3 flex items-center justify-between rounded-md outline outline-1 outline-surfaceBorder">
                                <div>
                                    <h2>Child</h2>
                                    <small>Age of under 18</small>
                                </div>
                                <InputNumber
                                    v-model="form.child"
                                    showButtons buttonLayout="horizontal"
                                    :min="0" :max="99" placeholder="0"
                                    :invalid="form.errors.child != null ? true : false"
                                    :disabled="form.adult < 1"
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
                        
                        <!-- Package -->
                        <CustomSectionCard index="2" title="Choose a package">
                            <template #subtitle>
                                <p>Please choose a package.</p>
                            </template>
                            <Button
                                v-for="item in tour.packages"
                                :key="item.id"
                                @click="setPackageId(item.id)"
                                plain :outlined="form.package_id !== item.id"
                                class="flex flex-1 justify-between"
                            >
                                <span>{{ item.name }}</span>
                                <span>{{ useFormatPrice(item.price) }}</span>
                            </Button>
                        </CustomSectionCard>

                        <CustomSectionCard index="3" title="Pickup Location">
                            <template #subtitle>
                                <p>Please select a pickup location.</p>
                            </template>
                            <Button
                                v-for="pickupLocation in tour.pickup_location"
                                :label="pickupLocation.location"
                                :key="pickupLocation.id"
                                @click="setPickupLocationId(pickupLocation.id)"
                                plain :outlined="form.pickup_location_id !== pickupLocation.id"
                                class="text-left"
                            />
                        </CustomSectionCard>

                        <!-- Traveller Details -->
                        <CustomSectionCard index="4" title="Add traveller details">
                            <template #subtitle>
                                <InlineMessage severity="info" class="mb-4">
                                    This traveller will serve as the contact person for the booking.
                                </InlineMessage>
                            </template>
                            <div class="flex flex-col gap-2">
                                <div class="flex gap-6">
                                    <TextInput 
                                        v-model="form.first_name" 
                                        label="First Name" 
                                        :error="form.errors.first_name" 
                                        :disabled="$page.props.auth.user?.first_name != null"
                                        required
                                    />
                                    <TextInput 
                                        v-model="form.last_name" 
                                        label="Last Name" 
                                        :error="form.errors.last_name" 
                                        :disabled="$page.props.auth.user?.last_name != null"
                                        required 
                                    />
                                </div>
                                <div class="flex gap-6">
                                    <TextInput 
                                        v-model="form.email" 
                                        label="Email Address" 
                                        :error="form.errors.email" 
                                        :disabled="$page.props.auth.user?.email != null"
                                        required
                                    />
                                    <PhoneInput
                                        v-model="form.phone_number" 
                                        label="Phone Number" 
                                        :error="form.errors.phone_number" 
                                        :disabled="$page.props.auth.user?.phone_number != null"
                                        required
                                    />
                                </div>
                            </div>
                        </CustomSectionCard>
                        
                        <!-- Additional Details -->
                        <CustomSectionCard index="5" title="Additional details" :error="form.errors.contact_method">
                            <TextInput v-model="form.note" label="Special Requests / Questions" type="textarea" />
                            <div class="flex flex-col gap-2">
                                <h5>How should we contact you?</h5>
                                <div class="flex gap-4">
                                    <Button
                                        v-for="method in contact_methods"
                                        :key="method.id"
                                        :label="method.method_name"
                                        :icon="getContactMethodIcon(method.method_name)"
                                        @click="setContactMethodId(method.id)"
                                        plain :outlined="form.contact_method_id !== method.id"
                                        class="flex-1"
                                    />
                                </div>
                            </div>
                        </CustomSectionCard>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="flex-col gap-6 min-w-96 max-w-96 h-fit hidden xl:flex">
            <Card>
                <template #image>
                    <img
                        src="https://static.travelstride.com/store/51/ad4f08f81843578f3ea235b037593e/b669206bb8425f9409b22d1a87c8d181.jpg"
                        class="rounded-t-md"
                    >
                </template>
                <div class="flex flex-col gap-2">
                    <div v-for="tour in tourDetails">
                        <h2 class="leading-tight">{{ tour.header }}</h2>
                        <p>{{ tour.info }}</p>
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
                <div v-else>
                    <div class="flex flex-col gap-2">
                        <div class="flex justify-between">
                            <span>{{ getSelectedPackage.name }}</span>
                            <span>{{ getSelectedPackagePrice }}</span>
                        </div>
                        <div class="flex justify-between">
                            <small>
                                {{ getNumberOfTraveller }}
                                Travellers x
                                {{ getSelectedPackagePrice }}
                            </small>
                            <span>{{ getTotalPrice }}</span>
                        </div>
                        <div v-if="hasDiscount" class="flex justify-between">
                            <span>Discount</span>
                            <span>{{ getDiscount }}</span>
                        </div>
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
                @click="submit"
            />
        </div>
    </div>
</template>