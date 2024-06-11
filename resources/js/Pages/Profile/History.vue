<script setup>
import ProfileLayout from '@/Layouts/ProfileLayout.vue';

import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import Tag from 'primevue/tag';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';

import { ref } from 'vue';
import dayjs from 'dayjs';

const props = defineProps({
    bookings: {
        type: [Array, Object],
    },
    reservations: {
        type: [Array, Object],
    },
    payments: {
        required: false,
    }
});

const expandedRows = ref({});
</script>

<template>
    <ProfileLayout section="History">
        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <section role="booking" class="mb-8">
                    <h1 class="mb-4">Booking History</h1>
                    <div v-if="bookings.length != 0" class="bg-white shadow sm:rounded-lg">
                        <DataTable v-model:expandedRows="expandedRows" :value="bookings" dataKey="id" tableStyle="min-width: 50rem">
                            <Column expander style="width: 5rem" />
                            <Column header="Date">
                                <template #body="{ data }">
                                    <span>{{ dayjs(data.created_at).format('DD MMM YYYY') }}</span>
                                </template>
                            </Column>
                            <Column field="tour.name" header="Tour Name" />
                            <Column field="payment.price" header="Price" />
                            <Column field="status" header="Status" />
                            <Column>
                                <template #body="{ data, index }">
                                    <!-- <Dropdown>
                                        <template #trigger>
                                            <button>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                                </svg>
                                            </button>
                                        </template>
                                        <template #content>
                                            <button
                                                class="block w-full px-4 py-2
                                                text-left text-sm leading-5 text-gray-700
                                                hover:bg-gray-100 focus:bg-gray-100
                                                transition duration-150 ease-in-out"
                                            >
                                                Edit
                                            </button>
                                            <DropdownLink as="button" method="delete">
                                                Delete
                                            </DropdownLink>
                                        </template>
                                    </Dropdown> -->
                                </template>
                            </Column>
                            <template #expansion="slotProps">
                                <div class="p-3">
                                    <h3>Booking Details</h3>
                                </div>
                            </template>
                        </DataTable>
                    </div>
                    <div v-else class="sm:p-4 bg-white shadow sm:rounded-lg">
                        <p>No booking history...</p>
                    </div>
                </section>
                <section role="reservation">
                    <h1 class="mb-4">Reservation History</h1>
                    <div v-if="reservations.length != 0" class="bg-white shadow sm:rounded-lg">
                        <DataTable v-model:expandedRows="expandedRows" :value="reservations" dataKey="id" tableStyle="min-width: 50rem">
                            <Column expander style="width: 5rem" />
                            <Column header="Date">
                                <template #body="{ data }">
                                    <span>{{ dayjs(data.created_at).format('DD MMM YYYY') }}</span>
                                </template>
                            </Column>
                            <Column field="tour.name" header="Tour Name" />
                            <!-- <Column field="payment.price" header="Price" /> -->
                            <Column header="Status">
                                <template #body="{ data }">
                                    <Tag severity="danger" value="Pending" />
                                </template>
                            </Column>
                            <Column header="Action" class="flex items-center justify-center">
                                <template #body="{ data, index }">
                                    <Button severity="danger" />
                                </template>
                            </Column>
                            <template #expansion="slotProps">
                                <div class="p-3">
                                    <h3>Reservation Details</h3>
                                </div>
                            </template>
                        </DataTable>
                    </div>
                    <div v-else class="sm:p-4 bg-white shadow sm:rounded-lg">
                        <p>No reservation history...</p>
                    </div>
                </section>
                <section role="reservation">
                    <h1 class="mb-4">Payment History</h1>
                    <div class="sm:p-4 bg-white shadow sm:rounded-lg">
                        <p>No payment history...</p>
                    </div>
                </section>
            </div>
        </div>
    </ProfileLayout>
</template>