<script setup>
import Button from 'primevue/button';

import ContactForm from '@/Components/Dialog/ContactForm.vue';
import Dialog from 'primevue/dialog';
import { ref } from 'vue';

const contactDialog = ref(false);
</script>

<template>
    <Dialog v-model:visible="contactDialog" modal header="Ask a Question" >
        <ContactForm @close-contact-dialog="contactDialog = false" />
    </Dialog>

    <footer class="bg-white w-full h-fit pt-8">
        <div class="mx-auto h-full w-5/6 xl:max-w-7xl">
            <!-- Top Section -->
            <section class="flex items-center place-content-start justify-between">
                <div id="section-1">
                    <SvgLogo />
                    <h3 class="text-lg font-bold">Travel Malaysia With Us!</h3>
                    <p class="xl:max-w-xl">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit,
                        sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                        Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut
                        aliquip ex ea commodo consequat.
                    </p>
                </div>
                <div id="section-2">
                    <h3 class="text-lg font-bold mb-2">Butiza Travel</h3>
                    <ul class="list-none font-medium text-gray-800 cursor-pointer">
                        <li @click="$inertia.get(route('about'))">About Us</li>
                        <li @click="$inertia.get(route('collab'))">Collaboration</li>
                        <li>Why Group Tour</li>
                    </ul>
                </div>
                <div id="section-3">
                    <h3 class="text-lg font-bold mb-2">Connect</h3>
                    <ul
                        class="list-none font-medium text-gray-800 cursor-pointer"
                        @click="contactDialog = true"
                    >
                        <li>Contact Us</li>
                        <!-- <li>Blog</li> -->
                    </ul>
                </div>
                <div id="section-4">
                    <h3 class="text-lg font-bold mb-2">Follow Us</h3>
                    <div class="flex gap-2">
                        <a href="https://www.facebook.com/ButizaTravelandTours?_rdr">
                            <Button icon="pi pi-facebook" text />
                        </a>
                        <a href="https://www.instagram.com/butiza.travelandtours/">
                            <Button icon="pi pi-instagram" text />
                        </a>
                    </div>
                </div>
            </section>
            <section class="mt-8 py-4 text-center">
                <small>XXXX - XXXX Butiza Travel. All rights reserved.</small>
            </section>
        </div>
    </footer>
</template>