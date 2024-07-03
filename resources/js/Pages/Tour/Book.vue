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
import Form from '@/Pages/Tour/Partials/Form.vue';

import { ref, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';

import dayjs from 'dayjs';
import { useFormatPrice } from '@/Composables/formatPrice.js'; 
import { useFormatText } from '@/Composables/formatText';

const paymentSection = ref(false);
const selectedContactMethod = ref(null);

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
    finished_date: props.availability.finished_date,
    adult: null,
    child: null,
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

const tourDetails = [
    {
        header: props.tour.name,
        info: `${props.tour.duration} days`
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

const bookingValidation = () => {
    bookingForm.post(route('booking.validate'), {
        onSuccess: () => paymentSection.value = true,
        onError: (error) => console.error(error),
    });
};

const paymentValidation = () => {
    paymentForm.post(route('validate-payment'), {
        onSuccess: () => bookingForm.post(route('booking.store', { id: props.availability.id })),
        onError: (error) => console.error(error),
        onFinish: () => bookingForm.post(route('booking.store', { id: props.availability.id })),
    });
};

// const datePlaceholder = () => {
//     return 'Departure Date: ' + dayjs(props.availability.departure_date).format('DD MMMM YYYY');
// };

</script>

<template>
    <MarginLayout>
        <div class="bg-white h-fit shadow py-6">
            <div class="flex items-center mx-auto w-11/12 lg:w-5/6 xl:max-w-7xl">
                <SvgLogo />
            </div>
        </div>

        <section class="my-8 mx-auto lg:flex w-11/12 lg:w-5/6 xl:max-w-7xl">
            <!-- <Avatar icon="pi pi-user" class="mt-3 bg-white" size="large" shape="circle" /> -->
            <div class="flex w-full">
                <div class="flex mt-3">
                    <Avatar icon="pi pi-user" class="bg-white mr-3" size="large" shape="circle" />
                    <div class="bg-white translate-y-full translate-x-1/2 origin-center rotate-45 size-4 z-50"></div>
                </div>
                <div class="mx-auto grow max-w-full mr-4">
                    <!-- Title -->
                    <section v-if="!paymentSection">
                        <!-- <div class="flex items-center">
                            <div class="bg-white rotate-45 size-4 absolute xl:left-[-6px]"></div>
                            <h1>Hey there, We just need a few details to book this tour.</h1>
                        </div> -->
                        <!-- Booking Details -->
                        <form>
                            <!-- Number of travellers -->
                            <Form :index="1" title="How many are travelling?">
                                <div class="bg-white relative px-6 py-3 flex items-center justify-between rounded-xl outline outline-1 outline-neutral-300">
                                    <h2>Adult</h2>
                                    <InputNumber
                                        v-model="bookingForm.adult"
                                        showButtons buttonLayout="horizontal"
                                        :min="0" :max="99" placeholder="0"
                                        :invalid="bookingForm.errors.adult != null ? true : false"
                                    >
                                        <template #incrementbuttonicon>
                                            <span class="pi pi-plus"></span>
                                        </template>
                                        <template #decrementbuttonicon>
                                            <span class="pi pi-minus"></span>
                                        </template>
                                    </InputNumber>
                                </div>
                                <div class="bg-white relative px-6 py-3 flex items-center justify-between rounded-xl outline outline-1 outline-neutral-300">
                                    <h2>Child</h2>
                                    <InputNumber
                                        v-model="bookingForm.child"
                                        showButtons buttonLayout="horizontal"
                                        :min="0" :max="99" placeholder="0"
                                        :invalid="bookingForm.errors.child != null ? true : false"
                                    >
                                        <template #incrementbuttonicon>
                                            <span class="pi pi-plus"></span>
                                        </template>
                                        <template #decrementbuttonicon>
                                            <span class="pi pi-minus"></span>
                                        </template>
                                    </InputNumber>
                                </div>
                                <InputError :message="bookingForm.errors.adult || bookingForm.errors.child" />
                            </Form>

                            <Form :index="2" title="Add traveller details">
                                <template #subtitle>
                                    <div class="pb-3">
                                        <h3>Lead Traveller</h3>
                                        <small>This traveller will serve as the contact person for the booking.</small>
                                    </div>
                                </template>
                                <div class="flex flex-col gap-2">
                                    <div class="flex gap-6">
                                        <TextInput v-model="bookingForm.first_name" label="First Name" :error="bookingForm.errors.first_name" required />
                                        <TextInput v-model="bookingForm.last_name" label="Last Name" :error="bookingForm.errors.last_name" required />
                                    </div>
                                    <div class="flex gap-6">
                                        <TextInput v-model="bookingForm.email" label="Email" :error="bookingForm.errors.email" required />
                                        <TextInput v-model="bookingForm.phone_number" label="Phone Number" type="tel" :error="bookingForm.errors.phone_number" required />
                                    </div>
                                </div>
                            </Form>

                            <Form :index="3" title="Additional details">
                                <TextInput v-model="bookingForm.note" label="Special Requests / Questions" type="textarea" />
                                <div class="flex flex-col gap-2">
                                    <h5>How should we contact you?</h5>
                                    <div class="flex gap-4">
                                        <Button
                                            v-for="method in contact_methods"
                                            :key="method.id"
                                            :label="method.method_name"
                                            :icon="getContactMethodIcon(method.method_name)" 
                                            @click="selectContactMethod(method.id)"
                                            rounded plain text outlined
                                            class="flex-1"
                                            :class="[{'bg-primary text-white': selectedContactMethod === method.id}]"
                                        />
                                    </div>
                                    <InputError :message="bookingForm.errors.contact_method" />
                                </div>
                            </Form>
                        </form>
                    </section>

                    <!-- Payment Section -->
                    <section v-else>
                        <div class="bg-white p-5 rounded-lg shadow">
                            <h1 class="mb-3">Next, add your payment details and confirm your booking!</h1>
                            <h4 class="mb-2">Select your payment method:</h4>
                            <TabView class="border rounded shadow mb-4">
                                <TabPanel header="Pay by card">
                                    <!-- Payment Form -->
                                    <form>
                                        <div class="flex gap-4 mb-2">
                                            <TextInput 
                                                v-model="paymentForm.cardholder_name" 
                                                label="Cardholder Name" 
                                                placeholder="e.g. JOHN SMITH" 
                                                :error="paymentForm.errors.cardholder_name"
                                                class="flex-1"
                                            />
                                            <!-- <TextInput v-model="paymentForm.card_number" type="number" label="Card Number" placeholder="**** **** **** ****" :error="paymentForm.errors.card_number" /> -->
                                            <div class="flex flex-1 flex-col">
                                                <label class="text-neutral-500 text-sm mb-1 ml-2">Card Number</label>
                                                <InputMask
                                                    v-model="paymentForm.card_number"
                                                    mask="9999 9999 9999 9999"
                                                    placeholder="**** **** **** ****"
                                                />
                                            </div>
                                        </div>
                                        <div class="flex gap-4 mb-4">
                                            <div class="flex flex-col flex-1">
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
                                        <h3 class="ml-2 mb-2">Billing Details</h3>
                                        <div class="flex gap-4">
                                            <TextInput v-model="paymentForm.billing_address" label="Address" :error="paymentForm.errors.billing_address" />
                                            <TextInput v-model="paymentForm.postal_code" label="Postal Code" type="number" :error="paymentForm.errors.postal_code" />
                                        </div>
                                    </form>
                                </TabPanel>
                            </TabView>
                            <div class="flex items-start">
                                <Checkbox v-model="paymentForm.terms" name="terms" binary :invalid="reactiveCheckbox" />
                                <small class="ml-2">
                                    I accept and agree to Butiza Travel & Tour's
                                    <a class="underline text-primary">Terms & Condition</a>,
                                    <a class="underline text-primary">Privacy Policy</a> and,
                                    <a class="underline text-primary">Payment Cancellation and Refund Condition</a>.
                                </small>
                            </div>
                        </div>
                    </section>
                    <small v-if="paymentSection" class="flex items-center ml-6 mt-2 cursor-pointer" @click="paymentSection = false">
                        <i class="pi pi-angle-left"></i> 
                        Change Booking Details
                    </small>
                </div>
            </div>
            
            <div class="min-w-72 max-w-72 h-fit hidden xl:block">

                <!-- Trip Details -->
                <div class="bg-white rounded-md shadow-md mb-8">
                    <img 
                        src="https://static.travelstride.com/store/51/ad4f08f81843578f3ea235b037593e/b669206bb8425f9409b22d1a87c8d181.jpg"
                        class="rounded-t-md"
                    >
                    <div class="flex flex-col p-3 gap-3">
                        <div v-for="tour in tourDetails">
                            <h2>{{ tour.header }}</h2>
                            <p>{{ tour.info }}</p>
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
                    label="Continue"
                    class="w-full justify-center" rounded
                    @click="bookingValidation"
                />
            </div>
        </section>
    </MarginLayout>
</template>