<script setup>
import TextInput from '@/Components/TextInput.vue';
import Card from '@/Components/Card.vue';
import { useForm } from '@inertiajs/vue3';

import Button from 'primevue/button';
import Divider from 'primevue/divider';
import FileUpload from 'primevue/fileupload';
import InputError from '@/Components/InputError.vue';
import Toast from 'primevue/toast';
import { useToast } from 'primevue/usetoast';

const toast = useToast();

import { computed, ref } from 'vue';
import axios from 'axios';

const props = defineProps({
    user: {
        type: Object,
        required: true,
    }
});

const form = useForm({
    business_name: props.user.partner.business_name,
    website: props.user.partner.website,
    country: props.user.partner.country,
    city: props.user.partner.city,
    registration_number: props.user.partner.registration_number ?? '',
    ssm_path: props.user.partner.ssm_path ?? '',
});

const preview = ref(form.ssm_path);

const saveChanges = () => {
    form.patch(route('partner-account.update', { partner: props.user.partner.id }), {
        preserveScroll: true,
        onSuccess: () => {
            toast.add({ severity: 'success', summary: 'Success', detail: 'Company information updated', life: 3000 });
            form.clearErrors();
        },
        onError: (error) => {
            console.log(error);
            toast.add({ severity: 'error', summary: 'Error', detail: 'Failed to update company information', life: 3000 });
        },
    });
};

const onUpload = async (file) => {
    const formData = new FormData();
    formData.append('file', file);

    try {
        const response = await axios.post(route('upload.store'), formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            }
        });

        return response;
    } catch (error) {
        if (error.response && error.response.status === 422) {
            console.error('Validation Failed:', error.response.data.errors);
        } else {
            console.error('Error:', error.response ? error.response.data : error.message);
        }
        throw error;
    }
};

const onChange = async (event) => {
    const file = event.target.files[0];

    const { data } = await onUpload(file);

    form.ssm_path = data.path;

    showPreview(file);
}

const showPreview = (file) => {
    const reader = new FileReader();
    reader.onload = (event) => {
        preview.value = event.target.result;
    };
    reader.readAsDataURL(file);
}

const removePreview = () => {
    // [1] Send request to remove temporary file using the path
    axios.delete(route('upload.destroy'), { path: form.ssm_path })
        .then(response => {
            console.log(response);
            preview.value = null;
        })
        .catch(error => {
            console.error(error);
        })
    // [Alternative] How do I reset preview.value with [1] method only
    // Does props update itself if we send [1] request?
}

const formChanged = computed(() => {
    return (
    form.business_name !== props.user.partner.business_name ||
        form.website !== props.user.partner.website ||
        form.country !== props.user.partner.country ||
        form.city !== props.user.partner.city ||
        form.registration_number !== (props.user.partner.registration_number ?? '') ||
        form.ssm_path !== (props.user.partner.ssm_path ?? '')
    );
});

</script>

<template>
    <Toast />
    <div class="flex flex-col gap-6">
        <div class="border border-neutral-300 rounded-md p-4">
            <div>
                <h2>Company Information</h2>
                <p class="mt-1 text-sm text-neutral-600">
                    Update your company's information. 
                </p>
                <Divider />
                <form @submit.prevent="saveChanges">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <TextInput
                            v-model="form.business_name"
                            label="Business Name"
                            :error="form.errors.business_name"
                            required
                        />
                        <TextInput
                            v-model="form.website"
                            label="Website"
                            :error="form.errors.website"
                            required
                        />
                        <TextInput
                            v-model="form.country"
                            label="Country"
                            :error="form.errors.country"
                            required
                        />
                        <TextInput
                            v-model="form.city"
                            label="City"
                            :error="form.errors.city"
                            required
                        />
                        <div class="col-span-2 flex flex-col gap-4">
                            <div class="flex flex-col gap-2">
                                <TextInput 
                                    v-model="form.registration_number"
                                    label="Registration Number"
                                    :error="form.errors.registration_number"
                                    :disabled="user.partner.registration_number != null"
                                />
                                <label  class="text-neutral-500 text-sm">SSM (Optional)</label>
                                <div id="CustomFileUpload" class="outline outline-1 outline-neutral-300 rounded-md"> 
                                     <div class="">
                                        <div v-if="!preview" class="flex flex-col text-center p-4">
                                            <span
                                                @click="$refs.fileInput.click()"
                                                class="text-primary font-semibold text-lg cursor-pointer"
                                            >
                                                Upload File
                                            </span>
                                            <small>Supported file types: JPG, JPEG, PNG, PDF</small>
                                            <input type="file" ref="fileInput" @change="onChange" accept="image/*" hidden />
                                        </div>
                                        <div v-else class="relative">
                                            <div class="absolute p-3 right-0">
                                                <Button
                                                    icon="pi pi-times" 
                                                    severity="danger" 
                                                    outlined rounded 
                                                    @click="removePreview()"
                                                />
                                            </div>
                                            <div class="flex justify-center">
                                                <!-- <img :src="preview" class="max-w-96" /> -->
                                                <img :src="preview" class="max-w-96" />
                                            </div>
                                        </div>
                                     </div>
                                 </div>
                                <InputError :message="form.errors.ssm_path" />
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <Button 
                            label="Save Changes"
                            type="submit"
                            class="mt-4"
                            :disabled="!formChanged"
                        />
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>