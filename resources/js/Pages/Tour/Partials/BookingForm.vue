<script setup>
import Form from '@/Pages/Tour/Partials/Form.vue';
import Card from '@/Components/Card.vue';
import TextInput from '@/Components/TextInput.vue';

import Button from 'primevue/button';
import InputNumber from 'primevue/inputnumber';
import Avatar from 'primevue/avatar';
import InlineMessage from 'primevue/inlinemessage';
import Divider from 'primevue/divider';
import Tag from 'primevue/tag';

import { ref, computed, reactive } from 'vue';
import { useForm } from '@inertiajs/vue3';

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
});

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

const getContactMethodIcon = (iconName) => {
    const icon = contactIcons.find(icon => iconName.toLowerCase() in icon );
    return icon ? icon[iconName.toLowerCase()] : '';
};

const setPackageId = (packageId) => form.package_id = packageId;
const setContactMethodId = (contactMethodId) => form.contact_method_id = contactMethodId;

const submit = () => {
    form.post(route('booking.store', { availability: props.availability.id}), {
        onSuccess: () => form.reset(),
        onError: (error) => console.error(error),
    });
};

const form = useForm({
    package_id: null,
    contact_method_id: null,
    departure_date: props.availability.departure_date,
    finished_date: props.availability.finished_date,
    adult: null,
    child: null,
    note: null,
    first_name: null,
    last_name: null,
    email: null,
    phone_number: null
});

function getSelectedPackage() {
    return props.tour.packages.find(item => item.id === form.package_id);
}

function getNumberOfTraveller() {
    return form.adult + form.child;
}

function getDiscount() {
    // 
}

const hasDiscount = computed(() => {
    // return props.tour.discount;
    return false;
});

const getSelectedPackagePrice = computed(() => {
    return useFormatPrice(getSelectedPackage().price);
});

const getTotalPrice = computed(() => {
    var total = getSelectedPackage().price * getNumberOfTraveller();
    return useFormatPrice(total);
});

const isNumberOfTravellerFilled = computed(() => {
    return getNumberOfTraveller() >= props.tour.min_pax;
});

const isPackageSelected = computed(() => {
    return form.package_id != null;
});

const isNecessaryFieldsFilled = computed(() => {
    return isNumberOfTravellerFilled.value && isPackageSelected.value;
});

const isFormValid = computed(() => {
    return (form.adult || form.child)
        && form.first_name
        && form.last_name
        && form.email
        && form.phone_number
        && form.contact_method_id
});

</script>

<template>
    <div class="flex w-full gap-6 my-6">
        <div class="flex w-full">
            <!-- <div class="flex mt-3">
                <Avatar icon="pi pi-user" class="bg-white mr-3" size="large" shape="circle" />
                <div class="bg-white translate-y-full translate-x-1/2 origin-center rotate-45 size-4 z-50"></div>
            </div> -->
            <div class="mx-auto grow max-w-full">
                <form>
                    <div class="flex flex-col gap-6">
                        <!-- Number of travellers -->
                        <Form :index="1" title="How many are travelling?" :error="form.errors.adult || form.errors.child">
                            <template #subtitle>
                                <p>Please select a minimum of {{ tour.min_pax }} travellers.</p>
                            </template>
                            <div class="bg-white relative px-6 py-3 flex items-center justify-between rounded-md outline outline-1 outline-neutral-300">
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
                            <div class="bg-white relative px-6 py-3 flex items-center justify-between rounded-md outline outline-1 outline-neutral-300">
                                <h2>Child</h2>
                                <InputNumber
                                    v-model="form.child"
                                    showButtons buttonLayout="horizontal"
                                    :min="0" :max="99" placeholder="0"
                                    :invalid="form.errors.child != null ? true : false"
                                >
                                    <template #incrementbuttonicon>
                                        <span class="pi pi-plus"></span>
                                    </template>
                                    <template #decrementbuttonicon>
                                        <span class="pi pi-minus"></span>
                                    </template>
                                </InputNumber>
                            </div>
                        </Form>
                        <!-- Package -->
                        <Form :index="2" title="Choose a package">
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

                        </Form>
                        <!-- Traveller Details -->
                        <Form :index="3" title="Add traveller details">
                            <template #subtitle>
                                <InlineMessage severity="info" class="mb-4">
                                    This traveller will serve as the contact person for the booking.
                                </InlineMessage>
                            </template>
                            <div class="flex flex-col gap-2">
                                <div class="flex gap-6">
                                    <TextInput v-model="form.first_name" label="First Name" :error="form.errors.first_name" required />
                                    <TextInput v-model="form.last_name" label="Last Name" :error="form.errors.last_name" required />
                                </div>
                                <div class="flex gap-6">
                                    <TextInput v-model="form.email" label="Email Address" :error="form.errors.email" required />
                                    <TextInput v-model="form.phone_number" label="Phone Number" type="tel" :error="form.errors.phone_number" required />
                                </div>
                            </div>
                        </Form>
                        <!-- Additional Details -->
                        <Form :index="4" title="Additional details" :error="form.errors.contact_method">
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
                        </Form>
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
                        v-for="activity in getSelectedPackage().activities" 
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
                            <span>{{ getSelectedPackage().name }}</span>
                            <span>{{ getSelectedPackagePrice }}</span>
                        </div>
                        <div class="flex justify-between">
                            <small>
                                {{ getNumberOfTraveller() }}
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