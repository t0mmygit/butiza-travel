<script setup>
import NavBar from '@/Components/NavBar.vue';
import Footer from '@/Components/Footer.vue';
import MarginLayout from '@/Layouts/MarginLayout.vue';

const array = [1, 2, 3, 4];
</script>

<template>
    <MarginLayout>
        <NavBar />
        <main class="w-full xl:max-w-7xl mx-auto">
            <h1 class="pt-8 text-2xl">Frequently Asked Questions</h1>
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 my-4">
                <div v-for="arr in array" class="bg-white p-4 h-96 shadow rounded-md cursor-pointer">
                    <h1>In Development</h1>
                    <p class="line-clamp-3">
                        Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."
                    </p>
                </div>
            </div>
        </main>
        <!-- <Footer class="flex place" /> -->
    </MarginLayout>
        
</template>