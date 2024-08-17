<script setup>
import Column from 'primevue/column';
import DataTable from 'primevue/datatable';
import Tag from 'primevue/tag';
import { computed } from 'vue';

const props = defineProps({
    reservations: {
        type: Object,
    },
});

const hasReservations = computed(() => props.reservations.length > 0);

</script>

<template>
    <div v-if="hasReservations" class="border border-surfaceBorder rounded sm:rounded-md">
        <DataTable :value="reservations">
            <Column field="reference" header="Reservation. No" />
            <Column field="package.tour.name" header="Tour Name" />
            <Column field="preferred_date" header="Preferred Date" />
            <Column header="Status">
                <template #body="{ data }">
                    <Tag :value="data.status" severity="info" />
                </template>
            </Column>
        </DataTable>
    </div>
    <p v-else>Nothing here yet...</p>
</template>