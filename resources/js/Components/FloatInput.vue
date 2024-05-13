<script setup>
import FloatLabel from 'primevue/floatlabel';
import InputText from 'primevue/inputtext';
import Password from 'primevue/password';
import { ref } from 'vue';

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
    },
    error: {
        type: String,
    }
})

const value = ref(null);

</script>

<!-- Merge two primevue's components  -->

<template>
    <div>
        <FloatLabel>
            <Password 
                v-if="type === 'password'"
                toggleMask
                :feedback="false"
            />
            <InputText 
                v-else 
                class="w-full"
            />
            <label>{{ label }}</label>
        </FloatLabel>
        <small class="text-red-500">{{ error }}</small>
    </div>
</template>