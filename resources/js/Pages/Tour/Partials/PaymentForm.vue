<script setup>
import Checkbox from 'primevue/checkbox';
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import InputGroup from 'primevue/inputgroup';
import InputGroupAddon from 'primevue/inputgroupaddon';
import InputMask from 'primevue/inputmask';
import Divider from 'primevue/divider';
import Avatar from 'primevue/avatar';

import { useFormatPrice } from '@/Composables/formatPrice.js'; 

const form = useForm({
    cardholder_name: null,
    card_number: null,
    card_month: null,
    card_year: null,
    card_cvv: null,
    billing_address: null,
    postal_code: null,
    terms: null,
});

const reactiveCheckbox = computed(() => form.errors.terms ? true : false);

const paymentValidation = () => {
    form.post(route('validate-payment'), {
        onSuccess: () => form.post(route('booking.store', { id: props.availability.id })),
        onError: (error) => console.error(error),
        onFinish: () => form.post(route('booking.store', { id: props.availability.id })),
    });
};

</script>

<template>
    <div clas="flex w-full">
        <div class="flex mt-3">
            <Avatar icon="pi pi-user" class="bg-white mr-3" size="large" shape="circle" />
            <div class="bg-white translate-y-full translate-x-1/2 origin-center rotate-45 size-4 z-50"></div>
        </div>
        <div class="mx-auto grow max-w-full mr-4">
            <div class="bg-white p-5 rounded-lg shadow">
                <h1 class="mb-3">Next, add your payment details and confirm your booking!</h1>
                <h4 class="mb-2">Select your payment method:</h4>
                <TabView class="border rounded shadow mb-4">
                    <TabPanel header="Pay by card">
                        <!-- Payment Form -->
                        <form>
                            <div class="flex gap-4 mb-2">
                                <TextInput 
                                    v-model="form.cardholder_name" 
                                    label="Cardholder Name" 
                                    placeholder="e.g. JOHN SMITH" 
                                    :error="form.errors.cardholder_name"
                                    class="flex-1"
                                />
                                <!-- <TextInput v-model="form.card_number" type="number" label="Card Number" placeholder="**** **** **** ****" :error="form.errors.card_number" /> -->
                                <div class="flex flex-1 flex-col">
                                    <label class="text-neutral-500 text-sm mb-1 ml-2">Card Number</label>
                                    <InputMask
                                        v-model="form.card_number"
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
                                            v-model="form.card_month"
                                            placeholder="MM"
                                            :min="1"
                                            :max="12"
                                            />
                                        <InputGroupAddon>/</InputGroupAddon>
                                        <InputNumber
                                            v-model="form.card_year"
                                            placeholder="YY"
                                            :useGrouping="false"
                                        />
                                    </InputGroup>
                                    <InputError
                                        v-if="form.errors.card_month || form.errors.card_year"
                                        message="The card expiry date is required."
                                    />
                                </div>
                                <TextInput v-model="form.card_cvv" label="CVV" type="number" placeholder="123" :error="form.errors.card_cvv" />
                            </div>
                            <h3 class="ml-2 mb-2">Billing Details</h3>
                            <div class="flex gap-4">
                                <TextInput v-model="form.billing_address" label="Address" :error="form.errors.billing_address" />
                                <TextInput v-model="form.postal_code" label="Postal Code" type="number" :error="form.errors.postal_code" />
                            </div>
                        </form>
                    </TabPanel>
                </TabView>
                <div class="flex items-start">
                    <Checkbox v-model="form.terms" name="terms" binary :invalid="reactiveCheckbox" />
                    <small class="ml-2">
                        I accept and agree to Butiza Travel & Tour's
                        <a class="underline text-primary">Terms & Condition</a>,
                        <a class="underline text-primary">Privacy Policy</a> and,
                        <a class="underline text-primary">Payment Cancellation and Refund Condition</a>.
                    </small>
                </div>
            </div>
        </div>

        <small class="flex items-center ml-6 mt-2 cursor-pointer" @click="paymentSection = false">
            <i class="pi pi-angle-left"></i> 
            Change Booking Details
        </small>
    </div>

    <div class="min-w-72 max-w-72 h-fit hidden xl:block">
        <div v-if="paymentSection" class="bg-white rounded-md shadow-md mb-8 p-3">
            <h2 class="mb-4">Price Breakdown</h2>
            <div class="flex justify-between">
                <small>{{ form.passenger }} 
                    Traveller<span v-if="form.passenger > 1">s</span> 
                    x {{ useFormatPrice(tour.base_price) }}
                </small>
                <small>{{ useFormatPrice(tour.base_price * form.passenger) }}</small>
            </div>
            <Divider />
            <div class="flex justify-between mb-4">
                <h3>Total Due</h3>
                <h3>{{ useFormatPrice(tour.base_price * form.passenger) }}</h3>
            </div>
            <Button 
                v-if="paymentSection"
                class="w-full justify-center"
                @click="paymentValidation"
            >Proceed Payment</Button>
        </div>
    </div>
</template>