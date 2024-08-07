<script setup>
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Tag from 'primevue/tag';

const props = defineProps({
    bookings: {
        type: Object,
    }
});

</script>

<template>
    <h1 class="mb-4">Booking History</h1>
    <div class="bg-white border border-surfaceBorder rounded sm:rounded-md">
        <DataTable
            :value="bookings"
            paginator :rows="5" :rowsPerPageOptions="[5, 10, 15]"
            class="rounded sm:rounded-md"
        >
            <Column field="id" header="Booking No." />
            <Column field="package.tour.name" header="Tour Name" />
            <Column header="Status">
                <template #body="{ data }">
                    <Tag
                        severity="info"
                        :value="data.trip_status"
                    />
                </template>
            </Column>
            <Column field="departure_date" header="Departure Date" />
            <Column field="finished_date" header="Finished Date" />
        </DataTable>
    </div>
</template>