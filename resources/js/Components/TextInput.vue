<script setup>
import { onMounted, ref, computed } from 'vue';
import Textarea from 'primevue/textarea';

const model = defineModel({
    type: [String, Number],
});

const props = defineProps({
    type: {
        type: String,
        default: 'text'
    },
    label: {
        type: String
    },
    error: {
        type: String
    },
    placeholder: {
        type: String
    },
    disabled: {
        type: Boolean,
        default: false
    },
    required: {
        type: Boolean,
    },
});

const input = ref(null);

// const classes = computed(() => 
//     props.error
//     ? 'border-red-400 focus:border-none rounded-md disabled:bg-neutral'
//     : 'border-neutral-300 border focus:border-primary-100 rounded-md disabled:bg-neutral'
// );
const classes = computed(() => 
    props.error
    ? 'border-red-error rounded-md disabled:bg-neutral'
    : 'border-neutral-300 border rounded-md disabled:bg-neutral'
);

</script>

<template>
    <div class="flex flex-col flex-1">    
        <label v-if="label" class="text-neutral-500 text-sm mb-1">{{ label }}
            <span v-if="required" class="text-error">*</span>
        </label>
        <input v-if="type != 'textarea'"
            :type="type"
            :placeholder="placeholder"
            :class="classes"
            v-model="model"
            :disabled="disabled"
        />
        <Textarea v-else 
            v-model="model" 
            :disabled="disabled"
            :rows="5"
        />
        <p class="text-error text-sm mt-2">{{ error }}</p>
    </div>
</template>
