<script setup>
import Button from 'primevue/button';
</script>

<template>
    <footer class="h-fit py-8 bg-white">
        <div id="section-container" class="m-auto lg:max-w-7xl">
            <div class="flex flex-row justify-around">
                <div id="section-1">
                    <SvgLogo />
                    <h3 class="text-lg font-bold">Travel Malaysia With Us!</h3>
                    <p class="xl:max-w-xl">
                        We helps you search and compare 50,000+ 
                        expert-planned trips from 1,000+ companies. 
                        Get the real picture with both traveler and expert reviews
                        to ensure the trip you book is the right one for you.
                    </p>
                </div>
                <div id="section-2">
                    <h3 class="text-lg font-bold mb-2">Butiza Travel</h3>
                    <ul class="list-none font-medium text-gray-800 cursor-pointer">
                        <li>About Us</li>
                        <li>Careers</li>
                        <li>Sign Up as a Guide</li>
                        <li>Member Benefits</li>
                        <li>Why Group Tour</li>
                    </ul>
                </div>
                <div id="section-3">
                    <h3 class="text-lg font-bold mb-2">Connect</h3>
                    <ul class="list-none font-medium text-gray-800 cursor-pointer">
                        <li>Contact Us</li>
                        <li>Write a Review</li>
                        <li>Blog</li>
                    </ul>
                </div>
                <div id="section-4">
                    <h3 class="text-lg font-bold mb-2">Follow Us</h3>
                    <div class="flex gap-2">
                        <a href="#">
                            <Button 
                                icon="pi pi-facebook" 
                                class="rounded-full"
                            />
                        </a>
                        <a href="#">
                            <Button icon="pi pi-instagram" class="rounded-full" />
                        </a>
                    </div>
                </div>
                
            </div>
        </div>
    </footer>
</template>