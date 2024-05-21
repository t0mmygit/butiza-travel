<script setup>
import { computed } from 'vue';

const props = defineProps({
    type: {
        type: String,
        default: 'button',
    },
    active: {
        type: Boolean,
        default: false,
    },
    icon: {
        type: Boolean,
        default: true
    }
});

const classes = computed(() => 
    props.active
        ? "inline-flex justify-center items-center px-4 py-2 gap-1 bg-white border border-indigo-500 rounded-md font-semibold text-xs text-indigo-500 uppercase tracking-widest shadow-sm focus:outline-none disabled:opacity-25 transition ease-in-out duration-150"       
        : "inline-flex justify-center items-center px-4 py-2 gap-1 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-indigo-500 hover:border-indigo-500 focus:outline-none disabled:opacity-25 transition ease-in-out duration-150"
)

const iconClasses = computed(() => 
    props.active
        ? "text-indigo-500 size-4"
        : "size-4"
)

</script>

<template>
    <button
        :type="type"
        :class="classes"
    >
        <svg v-if="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" :class="iconClasses">
            <path v-if="active" fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-1.814a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd" />
            <path v-else fill-rule="evenodd" d="M12 3.75a.75.75 0 0 1 .75.75v6.75h6.75a.75.75 0 0 1 0 1.5h-6.75v6.75a.75.75 0 0 1-1.5 0v-6.75H4.5a.75.75 0 0 1 0-1.5h6.75V4.5a.75.75 0 0 1 .75-.75Z" clip-rule="evenodd" />
        </svg>
        <slot />
    </button>
</template>
