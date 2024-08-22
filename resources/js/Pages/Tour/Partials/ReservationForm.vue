<script setup>
import { useFormatPrice } from '@/Composables/formatPrice';
import CustomSectionCard from '@/Components/CustomSectionCard.vue';

import TextInput from '@/Components/TextInput.vue';
import PhoneInput from '@/Components/PhoneInput.vue';

import Button from 'primevue/button';
import Calendar from 'primevue/calendar';
import InputNumber from 'primevue/inputnumber';
import InlineMessage from 'primevue/inlinemessage';

import { computed } from 'vue';

const props = defineProps({
    tour: {
        type: Object,
        required: true,
    },
    form: {
        type: Object,
        required: true
    },
    contact_methods: {
        type: Object,
        required: true,
    },
});

const getContactMethodIcon = (iconName) => {
    const icon = contactIcons.find(icon => iconName.toLowerCase() in icon );
    return icon ? icon[iconName.toLowerCase()] : '';
};

const contactIcons = [
    { call: 'pi pi-phone' },
    { email: 'pi pi-envelope' },
    { whatsapp: 'pi pi-whatsapp' }
];

const setPackageId = (packageId) => props.form.package_id = packageId;
const setPickupLocationId = (pickUpLocationId) => props.form.pickup_location_id = pickUpLocationId;
const setContactMethodId = (contactMethodId) => props.form.contact_method_id = contactMethodId;

// TODO: Make this dynamic instead (set the min date by admin)
const minDate = computed(() => {
    let today = new Date();
    let month = today.getMonth();
    let year = today.getFullYear();
    let date = today.getDate();

    return new Date(year, month, date);
});

</script>

<template>

    <form>
        <div class="flex flex-col gap-6">

            <CustomSectionCard 
                index="1" 
                title="Date Selection"
                :error="form.errors.preferred_date"
            >
                <template #subtitle>
                    <p>Please select a date.</p>
                </template>
                <Calendar
                    v-model="form.preferred_date"
                    :minDate="minDate"
                    inline
                />
            </CustomSectionCard>

            <CustomSectionCard 
                index="2" 
                title="Number of Travellers"
                :error="form.errors.adult || form.errors.child"
            >
                <template #subtitle>
                    <p>Please select a minimum of {{ tour.min_pax }} travellers.</p>
                </template>
                <div class="bg-white px-6 py-3 flex items-center justify-between rounded-md outline outline-1 outline-surfaceBorder">
                    <h2>Adult</h2>
                    <InputNumber
                        v-model="form.adult"
                        showButtons buttonLayout="horizontal"
                        :min="0" :max="99" placeholder="0"
                        :invalid="form.errors.adult != null ? true : false"
                    >
                        <template #incrementbuttonicon>
                            <i class="pi pi-plus"></i>
                        </template>
                        <template #decrementbuttonicon>
                            <i class="pi pi-minus"></i>
                        </template>
                    </InputNumber>
                </div>
                <div class="bg-white px-6 py-3 flex items-center justify-between rounded-md outline outline-1 outline-surfaceBorder">
                    <div>
                        <h2>Child</h2>
                        <small>Age of under 18</small>
                    </div>
                    <InputNumber
                        v-model="form.child"
                        showButtons buttonLayout="horizontal"
                        :min="0" :max="99" placeholder="0"
                        :invalid="form.errors.child != null ? true : false"
                        :disabled="form.adult < 1"
                    >
                        <template #incrementbuttonicon>
                            <i class="pi pi-plus"></i>
                        </template>
                        <template #decrementbuttonicon>
                            <i class="pi pi-minus"></i>
                        </template>
                    </InputNumber>
                </div>
            </CustomSectionCard>

            <CustomSectionCard index="3" title="Package Selection">
                <template #subtitle>
                    <p>Please choose a package.</p>
                </template>
                <Button
                    v-for="item in tour.packages"
                    :key="item.id"
                    @click="setPackageId(item.id)"
                    plain :outlined="form.package_id !== item.id"
                    class="flex flex-1 justify-between border-surfaceBorder"
                >
                    <span class="text-left">{{ item.name }}</span>
                    <span>{{ useFormatPrice(item.price) }}</span>
                </Button>
            </CustomSectionCard>

            <CustomSectionCard index="4" title="Pickup Location">
                <template #subtitle>
                    <p>Please select a pickup location.</p>
                </template>
                <Button
                    v-for="pickupLocation in tour.pickup_location"
                    :label="pickupLocation.location"
                    :key="pickupLocation.id"
                    @click="setPickupLocationId(pickupLocation.id)"
                    plain :outlined="form.pickup_location_id !== pickupLocation.id"
                    class="text-left border-surfaceBorder"
                />
            </CustomSectionCard>

            <CustomSectionCard index="5" title="Add traveller details">
                <template #subtitle>
                    <InlineMessage severity="info" class="mb-4">
                        This traveller will serve as the contact person for the booking.
                    </InlineMessage>
                </template>
                <div class="flex flex-col gap-2">
                    <div class="flex gap-6">
                        <TextInput 
                            v-model="form.first_name" 
                            label="First Name" 
                            :error="form.errors.first_name" 
                            :disabled="$page.props.auth.user?.first_name != null"
                            required
                        />
                        <TextInput 
                            v-model="form.last_name" 
                            label="Last Name" 
                            :error="form.errors.last_name" 
                            :disabled="$page.props.auth.user?.last_name != null"
                            required 
                        />
                    </div>
                    <div class="flex gap-6">
                        <TextInput 
                            v-model="form.email" 
                            label="Email Address" 
                            :error="form.errors.email" 
                            :disabled="$page.props.auth.user?.email != null"
                            required
                        />
                        <PhoneInput
                            v-model="form.phone_number" 
                            label="Phone Number" 
                            :error="form.errors.phone_number" 
                            :disabled="$page.props.auth.user?.phone_number != null"
                            required
                        />
                    </div>
                </div>
            </CustomSectionCard>

            <CustomSectionCard index="5" title="Additional details" :error="form.errors.contact_method">
                <TextInput v-model="form.note" label="Special Requests / Questions" type="textarea" />
                <div class="flex flex-col gap-2">
                    <h5>How should we contact you?</h5>
                    <div class="flex gap-4">
                        <Button
                            v-for="method in contact_methods"
                            :key="method.id"
                            :label="method.method_name"
                            :icon="getContactMethodIcon(method.method_name)"
                            @click="setContactMethodId(method.id)"
                            plain :outlined="form.contact_method_id !== method.id"
                            class="flex-1 border-surfaceBorder"
                        />
                    </div>
                </div>
            </CustomSectionCard>

        </div>
    </form>

</template>