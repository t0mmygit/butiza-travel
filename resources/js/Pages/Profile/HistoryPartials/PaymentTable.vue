<script setup>
import { useFormatPrice } from '@/Composables/formatPrice';

import Column from 'primevue/column';
import DataTable from 'primevue/datatable';
import Tag from 'primevue/tag';
import Button from 'primevue/button';
import OverlayPanel from 'primevue/overlaypanel';
import Menu from 'primevue/menu';

import { computed, ref } from 'vue';

import localizedFormat from 'dayjs/plugin/localizedFormat';
import dayjs from 'dayjs';
dayjs.extend(localizedFormat);

const props = defineProps({
    payments: {
        type: Object,
    },
});

const overlay = ref();

const items = (status) => {
    console.log(status);
    // Somehow it only take the latest record's status

    const items = ref([
        {
            label: 'View Invoice', 
            icon: 'pi pi-file', 
            visible: () => status === 'successful', 
            disabled: true,
        },
        {
            label: 'Retry Payment',
            icon: 'pi pi-refresh',
            visible: () => status === 'pending',
            disabled: true,
        },
        {
            label: 'Cancel Payment',
            icon: 'pi pi-trash',
            visible: () => status === 'pending',
            disabled: true,
        },
    ]);

    return items.value;
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

const isFailed = (status) => status === 'failed';
const toggle = (event) => overlay.value.toggle(event);

</script>

<template>
    <div v-if="hasPayments" class="border border-surfaceBorder rounded sm:rounded-md">
        <DataTable :value="payments">
            <Column field="id" header="Reference ID" />
            <Column header="Amount">
                <template #body="{ data }">
                    {{ useFormatPrice(data.amount) }}
                </template>
            </Column>
            <Column header="Payment Method">
                <template #body="{ data }">
                    <span v-if="data.method != null">
                        {{ data.number }}
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
                    <div class="">
                        <Button
                            v-if="! isFailed(data.status)"
                            icon="pi pi-ellipsis-h"
                            text rounded
                            @click="toggle"
                        />

                        <OverlayPanel  ref="overlay">
                            <Menu :model="items(data.status)" />
                        </OverlayPanel>
                    </div>
                </template>
            </Column>
        </DataTable>
    </div>
    <p v-else>Nothing here yet...</p>
</template>