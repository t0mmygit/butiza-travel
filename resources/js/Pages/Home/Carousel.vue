<script setup>
import { onMounted, onBeforeUnmount } from 'vue';

const images = [
    {
        url: 'https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
    },
    {
        url: 'https://images.unsplash.com/photo-1501785888041-af3ef285b470?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
    },
    {
        url: 'https://images.unsplash.com/photo-1469474968028-56623f02e42e?q=80&w=2074&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
    }
];

// Handle Automatic Switch between Images
onMounted(() => {
    const slides = document.querySelectorAll('.slide');
    let interval = null, slideIndex = 0;

    slides[0].classList.remove('hidden');
    
    interval = setInterval(() => {
        if (slideIndex >= slides.length) { slideIndex = 0; }

        slides.forEach(slide => slide.classList.add('hidden'));
        slides[slideIndex++].classList.remove('hidden');
    }, 3000);

    onBeforeUnmount(() => {
        clearInterval(interval);
    });
});

</script>

<template>
    <div class="relative w-full mx-auto">
        <div v-for="image in images" class="slide relative hidden">
            <img class="w-full h-[600px] object-cover" :src="image.url">
        </div>
        <!-- <a class="absolute left-0 top-1/2 p-4 -translate-y-1/2 bg-black/50 text-white cursor-pointer" onclick="moveSlide(-1)"><</a>
        <a class="absolute right-0 top-1/2 p-4 -translate-y-1/2 bg-black/50 text-white cursor-pointer" onclick="moveSlide(1)">></a> -->
    </div>
</template>