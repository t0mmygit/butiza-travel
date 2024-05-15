<script setup>
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import InputGroup from 'primevue/inputgroup';
import InputGroupAddon from 'primevue/inputgroupaddon';

// const state = ref(false);
</script>

<template>
    <div class="flex flex-col justify-center items-center p-6 mb-6">
        <div class="flex lg:justify-center lg:col-start-2 mb-4">
            <SvgLogo />
        </div>
        <h2 class="font-bold text-2xl mb-2">Sign in or create an account</h2>
        <p class="mb-6">Access member savings & community.</p>
        <InputGroup class="group max-w-xs">
            <InputGroupAddon class="group-hover:border-indigo-500 group-active:border-indigo-500">
                <i class="pi pi-envelope"></i>
            </InputGroupAddon>
            <InputText type="email" placeholder="Email Address" class="group-active:border-indigo-500 group-focus:border-indigo-500 transition-none" />
            <Button 
                label="Confirm" 
                class="bg-indigo-500 border-indigo-500" 
                @click="$emit('confirm')"
            />
        </InputGroup>   
        <div class="my-6">or</div>
        <div class="flex flex-col gap-4">
            <Button 
                label="Continue with Facebook" 
                icon="pi pi-facebook"
                plain text raised 
                class="shadow-none text-base outline outline-1 outline-gray-300 rounded hover:outline-indigo-500 hover:bg-white"
            />
            <Button
                label="Continue with Google"
                icon="pi pi-google"
                plain text raised
                class="shadow-none text-base outline outline-1 outline-gray-300 rounded hover:outline-indigo-500 hover:bg-white"
            />
        </div>
        <small class="text-gray-500 text-center mt-12">
            By signing in, you are agreeing to our <br> 
            <a href="#" class="underline">Privacy Policy</a>, and 
            <a href="#" class="underline">Terms of Use</a>.
        </small>
    </div>
</template>