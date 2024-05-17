<script setup>
import FloatLabel from 'primevue/floatlabel';
import InputText from 'primevue/inputtext';
import Password from 'primevue/password';
import { ref } from 'vue';

const model = defineModel({
    type: String,
});

defineProps({
    label: {
        type: String,
        required: true
    },
    type: {
        type: String,
        default: 'text'
    },
    value: {
        type: String,
        required: true
    },
    error: {
        type: String,
    }
})

const value = ref(null);

</script>

<!-- Merge two primevue's components  -->

<template>
    <div class="mb-2">
        <FloatLabel>
            <Password 
                v-if="type === 'password'"
                v-model="model"
                :id="value"
                toggleMask
                :feedback="false"
            />
            <InputText
                v-else 
                v-model="model"
                class="w-full"
            />
            <label :for="value">{{ label }}</label>
        </FloatLabel>
        <small class="text-red-500">{{ error }}</small>
    </div>
</template>