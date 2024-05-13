<script setup>
import { computed, ref } from 'vue';

const props = defineProps({
    align: {
        type: String,
        default: 'right',
    },
    width: {
        type: String,
        default: '48',
    },
    contentClasses: {
        type: String,
        default: 'py-1 bg-white',
    },
    label: {
        type: String,
        required: true
    }
});

// const closeOnEscape = (e) => {
//     if (open.value && e.key === 'Escape') {
//         open.value = false;
//     }
// };

// onMounted(() => document.addEventListener('keydown', closeOnEscape));
// onUnmounted(() => document.removeEventListener('keydown', closeOnEscape));

const widthClass = computed(() => {
    return {
        48: 'w-48',
    }[props.width.toString()];
});

const alignmentClasses = computed(() => {
    if (props.align === 'left') {
        return 'ltr:origin-top-left rtl:origin-top-right start-0';
    } else if (props.align === 'right') {
        return 'ltr:origin-top-right rtl:origin-top-left end-0';
    } else {
        return 'origin-top';
    }
});

const open = ref(false)
</script>

<template>
    <div class="relative">
        <div @click="open = !open" class="cursor-pointer px-2 py-3">
            <div class="flex justify-between">
                <h3 class="font-bold text-lg">{{ label }}</h3>
                <button v-if="!open">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                        <path fill-rule="evenodd" d="M12.53 16.28a.75.75 0 0 1-1.06 0l-7.5-7.5a.75.75 0 0 1 1.06-1.06L12 14.69l6.97-6.97a.75.75 0 1 1 1.06 1.06l-7.5 7.5Z" clip-rule="evenodd" />
                    </svg>
                </button>
                <button v-else>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                        <path fill-rule="evenodd" d="M11.47 7.72a.75.75 0 0 1 1.06 0l7.5 7.5a.75.75 0 1 1-1.06 1.06L12 9.31l-6.97 6.97a.75.75 0 0 1-1.06-1.06l7.5-7.5Z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- <div v-show="open" class="fixed inset-0 z-40" @click="open = false"></div> -->

        <Transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-100"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-95"
        >
            <div
                v-show="open"
                class="mt-2 rounded-md w-full"
                :class="[alignmentClasses]"
                style="display: none"
            >
                <div class="pb-6 px-2 flex flex-col gap-6">
                    <slot />
                </div>
            </div>
        </Transition>
    </div>
    <!-- <div class="bg-gray-200 h-0.5 rounded"></div> -->
</template>