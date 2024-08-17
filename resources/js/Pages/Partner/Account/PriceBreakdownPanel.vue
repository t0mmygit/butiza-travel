<script setup>
import Panel from 'primevue/panel';
import InlineMessage from 'primevue/inlinemessage';
import Divider from 'primevue/divider';
import { useFormatPrice } from '@/Composables/formatPrice';
import { computed, watch } from 'vue';

const props = defineProps({
    minTraveller: {
        type: [Number, null],
    },
    tourPackage: {
        type: [Object, null],
    },
    travellers: {
        type: [Number, null],
    },
    discount: {
        type: [Object, null],
    }
}); 

const emit = defineEmits(['update:amount']);

const isPackageAndNumberOfTravellersFilled = computed(
        () => props.tourPackage != null && (props.travellers > 0));

const calculatePackageTotalPrice = computed(() => props.tourPackage?.price * props.travellers);
const calculateDiscount = computed(
        () => calculatePackageTotalPrice.value * (props.discount?.percentage / 100));

const calculateTotalPriceAfterDiscount = computed(
        () => calculatePackageTotalPrice.value - calculateDiscount.value);

const displayPackagePrice = computed(() => useFormatPrice(props.tourPackage?.price));
const displayPackageTotalPrice = computed(() => useFormatPrice( calculatePackageTotalPrice ));

const displayDiscount = computed(() => useFormatPrice( calculateDiscount, 2, false, '-'));

const displayTotalPriceAfterDiscount = computed(
        () => useFormatPrice( calculateTotalPriceAfterDiscount, 2, false));

const hasDiscount = computed(() => props.discount != null);

watch(calculateTotalPriceAfterDiscount, (newValue) => {
    emit('update:amount', newValue);
});

</script>

<template>
    <Panel header="Price Breakdown" class="flex-1">
        <InlineMessage v-if="! isPackageAndNumberOfTravellersFilled" severity="secondary">
            Your price breakdown will be displayed here.
        </InlineMessage>
        <div v-else class="flex flex-col">
            <div class="flex gap-2 justify-between">
                <span>{{ tourPackage?.name }}</span>
                <span>{{ displayPackagePrice }}</span>
            </div>

            <div class="flex justify-between">
                <small>
                    {{ travellers }}
                    Travellers x
                    {{ displayPackagePrice }}
                </small>
                <small>{{ displayPackageTotalPrice }}</small>
            </div>
            
            <div v-if="hasDiscount" class="flex justify-between mt-4">
                <div class="flex flex-col">
                    <span>{{ discount.percentage }}% Discount</span>
                    <small>{{ discount.type }}</small>
                </div>
                <span class="text-error">{{ displayDiscount }}</span>
            </div>

            <Divider />
            
            <div class="flex justify-between">
                <h3>Total Due</h3>
                <h3>{{ displayTotalPriceAfterDiscount }}</h3>
            </div>
        </div>
    </Panel>
</template>