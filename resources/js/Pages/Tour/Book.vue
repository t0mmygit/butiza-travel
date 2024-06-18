<script setup>
import MarginLayout from '@/Layouts/MarginLayout.vue';
import Avatar from 'primevue/avatar';
import Button from 'primevue/button';
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import InputNumber from 'primevue/inputnumber';
import InputGroup from 'primevue/inputgroup';
import InputGroupAddon from 'primevue/inputgroupaddon';
import InputMask from 'primevue/inputmask';
import Checkbox from 'primevue/checkbox';
import Divider from 'primevue/divider';

import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';

import { ref, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';

import dayjs from 'dayjs';
import { useFormatPrice } from '@/Composables/formatPrice.js'; 

const paymentSection = ref(false);
const selectedContactMethod = ref(null);

const props = defineProps({
    tour: {
        type: Object,
        required: true
    },
    availability: {
        type: Object,
        required: true
    },
    contact_methods: {
        type: Object,
        required: true
    }
});

const contactIcons = [
    { call: 'pi pi-phone' },
    { email: 'pi pi-envelope' },
    { whatsapp: 'pi pi-whatsapp' }
];

const getContactMethodIcon = (iconName) => {
    const icon = contactIcons.find(icon => iconName.toLowerCase() in icon );
    return icon ? icon[iconName.toLowerCase()] : '';
};

const selectContactMethod = (contactMethodId) => selectedContactMethod.value = contactMethodId;

const reactiveTour = computed(() => props.tour.id);
const reactiveContactMethod = computed(() => selectedContactMethod.value);
const reactiveCheckbox = computed(() => paymentForm.errors.terms ? true : false);

const bookingForm = useForm({
    tour_id: reactiveTour,
    contact_method: reactiveContactMethod,
    departure_date: props.availability.departure_date,
    passenger: null,
    note: null,
    first_name: null,
    last_name: null,
    email: null,
    phone_number: null
});

const paymentForm = useForm({
    cardholder_name: null,
    card_number: null,
    card_month: null,
    card_year: null,
    card_cvv: null,
    billing_address: null,
    postal_code: null,
    terms: null,
});

const bookingValidation = () => {
    bookingForm.post(route('tour.validate-booking'), {
        onSuccess: () => paymentSection.value = true,
        onError: (error) => console.error(error),
    });
};

const paymentValidation = () => {
    paymentForm.post(route('validate-payment'), {
        onSuccess: () => bookingForm.post(route('tour.store-booking', { id: props.availability.id })),
        onError: (error) => console.error(error),
        onFinish: () => bookingForm.post(route('tour.store-booking', { id: props.availability.id })),
    });
};

const datePlaceholder = () => {
    return 'Departure Date: ' + dayjs(props.availability.departure_date).format('DD MMMM YYYY');
};

</script>

<template>
    <MarginLayout>
        <div class="bg-white h-fit shadow py-6">
            <div class="max-w-sm lg:max-w-full lg:mx-16 flex items-center relative">
                <SvgLogo />
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
                    <!-- Title -->
                    <section v-if="!paymentSection">
                        <div class="flex items-center mb-6">
                            <div class="bg-white rotate-45 size-4 absolute xl:left-[-6px]"></div>
                            <h1>Hey there, We just need a few details to book this tour.</h1>
                        </div>
                        <!-- Booking Details -->
                        <form>
                            <div class="mb-4 flex flex-col gap-2">
                                <TextInput :placeholder="datePlaceholder()" disabled />
                                <InputError :message="bookingForm.errors.departure_date" />
                            </div>
                            <div class="mb-8 flex flex-col gap-2">
                                <h4>How many people are traveling? *</h4>
                                <TextInput v-model="bookingForm.passenger" type="number" :error="bookingForm.errors.passenger" />
                            </div>
                            <div class="mb-8 flex flex-col gap-2">
                                <h2>Special Request & Questions</h2>
                                <textarea
                                    v-model="bookingForm.note"
                                    placeholder="Feel free to tell us anything you'd like or how you want to make the trip perfect for you. (optional)"
                                    class="rounded-md border-neutral-300 shadow"
                                ></textarea>
                                <InputError :message="bookingForm.errors.note" />
                            </div>
                            <div class="mb-8 flex flex-col gap-2">
                                <h2>Contact Info</h2>
                                <div class="flex gap-6 mt-4">
                                    <TextInput v-model="bookingForm.first_name" label="First Name *" :error="bookingForm.errors.first_name" />
                                    <TextInput v-model="bookingForm.last_name" label="Last Name *" :error="bookingForm.errors.last_name" />
                                </div>
                                <div class="flex gap-6 mt-4">
                                    <TextInput v-model="bookingForm.email" label="Email *" :error="bookingForm.errors.email" />
                                    <TextInput v-model="bookingForm.phone_number" label="Phone Number *" type="tel" inputmode="numeric" pattern="[0-9]*" :error="bookingForm.errors.phone_number" />
                                </div>
                            </div>
                            <div class="mb-8 flex flex-col gap-2">
                                <h2>How should we contact you?</h2>
                                <div class="flex gap-4">
                                    <Button
                                        v-for="method in contact_methods"
                                        :key="method.id"
                                        :label="method.method_name"
                                        :icon="getContactMethodIcon(method.method_name)"
                                        plain text raised
                                        class="flex-1 shadow-none text-base outline outline-1 outline-gray-300 rounded hover:outline-indigo-500 hover:bg-white"
                                        :class="[{'border-primary bg-primary-100 text-black': selectedContactMethod === method.id}]"
                                        @click="selectContactMethod(method.id)"
                                    />
                                </div>
                                <InputError :message="bookingForm.errors.contact_method" />
                            </div>
                        </form>
                    </section>
                    <!-- Payment Section -->
                    <section v-else>
                        <div class="flex items-center mb-6">
                            <div class="bg-white rotate-45 size-4 absolute xl:left-[-6px]"></div>
                            <h1>Next, add your payment details and confirm your booking!</h1>
                        </div>
                        <h4 class="mb-2">Select your payment method:</h4>
                        <TabView class="border rounded shadow-sm">
                            <TabPanel header="Pay by card">
                                <!-- Payment Form -->
                                <form>
                                    <div class="flex gap-4 mb-2">
                                        <TextInput v-model="paymentForm.cardholder_name" label="Cardholder Name" placeholder="e.g. JOHN SMITH" :error="paymentForm.errors.cardholder_name" />
                                        <!-- <TextInput v-model="paymentForm.card_number" type="number" label="Card Number" placeholder="**** **** **** ****" :error="paymentForm.errors.card_number" /> -->
                                        <div class="flex flex-col">
                                            <label class="text-neutral-500 text-sm mb-1 ml-2">Card Number</label>
                                            <InputMask
                                                v-model="paymentForm.card_number"
                                                mask="9999 9999 9999 9999"
                                                placeholder="**** **** **** ****"
                                            />
                                        </div>
                                    </div>
                                    <div class="flex gap-4 mb-4">
                                        <div class="flex flex-col w-1/2">
                                            <label class="text-neutral-500 text-sm mb-1 ml-2">Expiry Date</label>
                                            <InputGroup>
                                                <InputNumber 
                                                    v-model="paymentForm.card_month" 
                                                    placeholder="MM"
                                                    :min="1"
                                                    :max="12"
                                                 />
                                                <InputGroupAddon>/</InputGroupAddon>
                                                <InputNumber 
                                                    v-model="paymentForm.card_year" 
                                                    placeholder="YY" 
                                                    :useGrouping="false"
                                                />
                                            </InputGroup>
                                            <InputError 
                                                v-if="paymentForm.errors.card_month || paymentForm.errors.card_year" 
                                                message="The card expiry date is required." 
                                            />
                                        </div>
                                        <TextInput v-model="paymentForm.card_cvv" label="CVV" type="number" placeholder="123" :error="paymentForm.errors.card_cvv" />
                                    </div>
                                    <h3 class="mt-4 ml-2 mb-2">Billing Details</h3>
                                    <div class="flex gap-4">
                                        <TextInput v-model="paymentForm.billing_address" label="Address" :error="paymentForm.errors.billing_address" />
                                        <TextInput v-model="paymentForm.postal_code" label="Postal Code" type="number" :error="paymentForm.errors.postal_code" /> 
                                    </div>
                                </form>
                            </TabPanel>
                        </TabView>
                        <div class="flex items-start mt-8 ml-2">
                            <Checkbox v-model="paymentForm.terms" name="terms" binary :invalid="reactiveCheckbox" />
                            <p class="ml-2">
                                I accept and agree to Butiza Travel & Tour's
                                <a class="underline text-primary">Terms & Condition</a>,  
                                <a class="underline text-primary">Privacy Policy</a> and, 
                                <a class="underline text-primary">Payment Cancellation and Refund Condition</a>.
                            </p>
                        </div>
                    </section>
                </div>
                <small v-if="paymentSection" class="flex items-center ml-6 mt-2 cursor-pointer" @click="paymentSection = false">
                    <i class="pi pi-angle-left"></i> 
                    Change Booking Details
                </small>
            </div>
            <div class="min-w-72 max-w-72 h-fit hidden xl:block">
                <div class="bg-white rounded-md shadow-md mb-8">
                    <img 
                        src="https://static.travelstride.com/store/51/ad4f08f81843578f3ea235b037593e/b669206bb8425f9409b22d1a87c8d181.jpg"
                        class="rounded-t-md"
                    >
                    <div class="p-3">
                        <h2>{{ tour.name }}</h2>
                        <div class="flex items-center justify-between">
                            <span class="text-xl font-medium">{{ tour.day }} Days</span>
                            <span class="text-xl font-medium"><small class="text-black">From</small> {{ useFormatPrice(tour.base_price) }}</span>
                        </div>
                    </div>
                </div>
                <div v-if="paymentSection" class="bg-white rounded-md shadow-md mb-8 p-3">
                    <h2 class="mb-4">Price Breakdown</h2>
                    <div class="flex justify-between">
                        <small>{{ bookingForm.passenger }} 
                            Traveller<span v-if="bookingForm.passenger > 1">s</span> 
                            x {{ useFormatPrice(tour.base_price) }}
                        </small>
                        <small>{{ useFormatPrice(tour.base_price * bookingForm.passenger) }}</small>
                    </div>
                    <Divider />
                    <div class="flex justify-between mb-4">
                        <h3>Total Due</h3>
                        <h3>{{ useFormatPrice(tour.base_price * bookingForm.passenger) }}</h3>
                    </div>
                    <Button 
                        v-if="paymentSection"
                        class="w-full justify-center"
                        @click="paymentValidation"
                    >Proceed Payment</Button>
                </div>
                <Button 
                    v-if="!paymentSection"
                    class="w-full justify-center"
                    @click="bookingValidation"
                >Continue</Button>
            </div>
        </section>
    </MarginLayout>
</template>