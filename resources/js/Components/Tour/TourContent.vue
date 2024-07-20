<script setup>
const props = defineProps({
    tour: {
        type: Object,
    },
    title: {
        type: String,
        required: true
    },
    titleCenter: {
        type: Boolean,
    },
    gradient: {
        type: Boolean
    }
});

const titleClass = () => 
    props.titleCenter 
    ? 'flex col-span-2 items-center text'
    : 'flex col-span-2'

const rootClass = () => 
    props.gradient
    ? 'bg-gradient-to-r from-primary-200 to-primary-400 grid grid-cols-6 py-6 px-5 h-fit mt-6 rounded-lg shadow' 
    : 'bg-white grid grid-cols-6 py-6 px-5 h-fit mt-6 rounded-lg shadow'

</script>

<template>
    <div :class="rootClass()">
        <header :class="titleClass()">
            <h1 class="text-2xl">{{ title }}</h1>
        </header>
        <main class="col-start-3 col-span-4">
            <div class="flex flex-col gap-8">
                <slot />
            </div>
        </main>
    </div>
</template>