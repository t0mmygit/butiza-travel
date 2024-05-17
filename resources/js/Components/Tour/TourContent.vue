<script setup>
defineProps({
    tour: {
        type: Object,
    },
    title: {
        type: String,
        required: true
    }
})
</script>

<template>
    <div class="bg-white grid grid-cols-6 py-6 px-5 h-fit mt-6 rounded-md shadow">
        <header>
            <h1>{{ title }}</h1>
        </header>
        <main class="col-start-3 col-span-4">
            <div>
                <slot />
            </div>
        </main>
    </div>
</template>