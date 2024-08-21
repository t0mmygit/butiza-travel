<script setup>
import { useFormatPrice } from '@/Composables/formatPrice';

import Column from 'primevue/column';
import DataTable from 'primevue/datatable';
import Tag from 'primevue/tag';
import SplitButton from 'primevue/splitbutton';

import { computed, ref } from 'vue';
import { router } from '@inertiajs/vue3';

import localizedFormat from 'dayjs/plugin/localizedFormat';
import dayjs from 'dayjs';
dayjs.extend(localizedFormat);

const props = defineProps({
    payments: {
        type: Object,
    },
});

const items = (data) => {
    const status = ref(data.status);

    switch (status.value) {
        case 'successful':
            return successItems(data);
        case 'pending': 
            return pendingItems(data);
    }
}

const successItems = (data) => {
    return [
        {
            label: 'View Invoice',
            icon: 'pi pi-file',
        },
    ];
}

const pendingItems = (data) => {
    return [
        {
            label: 'Retry Payment',
            icon: 'pi pi-refresh',
            command: () => retryPayment(data),
        },
    ];
}

function retryPayment(data) {
    router.get(route('payment.edit', { payment: data.id }));
}

function handleTagSeverity(status) {
    switch (status) {
        case 'successful':
            return 'success';
        case 'failed':
            return 'danger';
        default:
            return 'warning';
    }
}

const hasPayments = computed(() => props.payments.length > 0);

const displayAmount = amount => useFormatPrice(parseFloat(amount), 2, false)
const isCancelled = status => status === 'cancelled';
const isFailed = status => status === 'failed';

</script>

<template>
    <div v-if="hasPayments" class="border border-surfaceBorder rounded sm:rounded-md">
        <DataTable 
            :value="payments"
            paginator
            :rows="5" :rowsPerPageOptions="[5, 10, 20, 50]"
        >
            <Column field="reference" header="Reference No." />
            <Column header="Amount">
                <template #body="{ data }">
                    {{ displayAmount(data.amount) }}
                </template>
            </Column>
            <Column header="Payment Method">
                <template #body="{ data }">
                    <span v-if="data.method != null">
                        {{ data.method }}
                    </span>
                    <p v-else>N/A</p>
                </template>
            </Column>
            <Column header="Status">
                <template #body="{ data }">
                    <Tag :value="data.status" :severity="handleTagSeverity(data.status)" />
                </template>
            </Column>
            <Column field="created_at" header="Date">
                <template #body="{ data }">
                    {{ dayjs(data.created_at).format('lll') }}
                </template>
            </Column>
            <Column>
                <template #body="{ data }">
                    <SplitButton
                        v-if="!isCancelled(data.status) && !isFailed(data.status)"
                        :model="items(data)"
                        menuButtonIcon="pi pi-ellipsis-h"
                        pt:button:root:class="hidden"
                        pt:menuButton:root:class="rounded-full"
                        text
                    />
                </template>
            </Column>
        </DataTable>
    </div>
    <p v-else>Nothing here yet...</p>
</template>