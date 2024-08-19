<script setup>
import TextInput from '@/Components/TextInput.vue';
import Button from 'primevue/button';
import { useFormatPrice } from '@/Composables/formatPrice';
import { router, useForm, usePage } from '@inertiajs/vue3';
import Toast from 'primevue/toast';
import { useToast } from 'primevue/usetoast';

const props = defineProps({
    payment: {
        type: [Array, Object],
    }
});


const paymentMethod = [
    { label: 'Card', value: 'card' },
    { label: 'Paypal', value: 'paypal' },
];

const toast = useToast();

const form = useForm({
    method: 'card',
    status: 'successful',
});

const submit = () => {
    // TODO: Encrypt the payment ID
    form.patch(route('payment.update', { id: props.payment.id }), {
        onError: (error) => handleToast('error', 'Error', `Failed to update payment method. ${error.response.data.message}`),
        onFinish: () => form.reset(),
    });
};

const handleToast = (severity, summary, detail, life = 3000) => {
    toast.add({ severity: severity, summary: summary, detail: detail, life: life })
};

const selectMethod = (method) => {
    form.method = method;
};

const getTourName = () => {
    return props.payment.booking.package.tour.name;
}

const getPaymentPackagePrice = () => {
    return useFormatPrice(props.payment.booking.package.price);
}

</script>

<template>
    <Toast />
    <main class="flex flex-row w-screen h-screen">
        <section class="bg-neutral-200 flex h-full p-16">
            <div class="flex flex-col">
                <ApplicationLogo class="mb-8" />
                <div>
                    <h1>Summary</h1>
                    <div>{{ getTourName() }}</div>
                    <div>{{ getPaymentPackagePrice() }}</div>
                </div>
            </div>
        </section>
        <section class="w-screen h-screen">
            <div class="p-16 h-full">
                <div class="max-w-2xl">
                    <h1 class="mb-8">Payment Details</h1>
                    <form @submit.prevent="submit">
                        <div class="flex flex-col gap-4">
                            <div>
                                <h3>Payment method</h3>
                                <div class="flex gap-4">
                                    <Button
                                        v-for="method in paymentMethod"
                                        :label="method.label"
                                        @click="selectMethod(method.value)"
                                        plain outlined
                                        class="flex-1"
                                    />
                                </div>
                            </div>
                            <div class="flex flex-col">
                                <TextInput label="Card Number" />
                                <div class="flex gap-4">
                                    <TextInput label="Expiry Date" />
                                    <TextInput label="CVV" />
                                </div>
                                <TextInput label="Cardholder Name" />
                            </div>
                            <Button
                                type="submit"
                                label="Confirm Payment"
                                class="w-full"
                            />
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </main>
</template>