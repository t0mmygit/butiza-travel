<script setup>
import ProfileLayout from '@/Layouts/ProfileLayout.vue';

import DataTable from 'primevue/datatable';
import Column from 'primevue/column';

import dayjs from 'dayjs';

const props = defineProps({
    reviews: {
        type: [Array, Object],
    }
});

</script>

<template>
    <ProfileLayout section="Review">
        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <h1>My Reviews</h1>
                <div v-if="reviews.length != 0" class="bg-white shadow rounded-lg">
                    <DataTable v:model:expandedRows="expandedRows" :value="reviews" tableStyle="min-wdith: 50rem">
                        <Column header="Published Date">
                            <template #body="{ data }">
                                {{ dayjs(data.created_at).format('DD MMMM YYYY') }}
                            </template>
                        </Column>
                        <Column header="Tour">
                            <template #body="{ data }">
                                {{ data.tour.name }}
                            </template>
                        </Column>
                        <Column header="Title">
                            <template #body="{ data }">
                                {{ data.title }}
                            </template>
                        </Column>
                        <Column header="Review">
                            <template #body="{ data }">
                                {{ data.content }}
                            </template>
                        </Column>
                    </DataTable>    
                </div>
                <div v-else class="bg-white p-4 shadow rounded-lg">
                    <p>No reviews yet</p>
                </div>
            </div>
        </div>
    </ProfileLayout>
</template>