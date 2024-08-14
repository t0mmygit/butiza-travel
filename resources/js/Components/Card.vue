<script setup>
</script>

<template>
    <div class="bg-white rounded-md shadow-sm border border-surfaceBorder"> 
        <slot name="image" />
        <div class="flex flex-col gap-2 p-4">
            <slot />
        </div>
    </div>
</template>