<script setup>
</script>

<template>
    <div class="bg-white outline outline-2 outline-primary-300 flex rounded-lg items-center justify-center flex-1 min-w-fit min-h-fit p-4">
        <header>
            <div id="icon" class="flex items-center">
                <slot name="header" />
            </div>
        </header>
        <main>
            <div id="info" class="ml-4 text-left">
                <slot name="main" />
            </div>
        </main>
    </div>
</template>