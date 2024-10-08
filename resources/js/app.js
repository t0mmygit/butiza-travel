// PrimeVue CSS
import '../css/app.css';
import 'primeicons/primeicons.css';
import 'primevue/resources/themes/aura-light-indigo/theme.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

// PrimeVue Plugin 
import PrimeVue from 'primevue/config';
import ToastService from 'primevue/toastservice';
import ConfirmationService from 'primevue/confirmationservice';
import BadgeDirective from 'primevue/badgedirective';

import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import ModalAuthenticate from '@/Components/Modal/ModalAuthentication.vue';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(PrimeVue)
            .use(ToastService)
            .use(ConfirmationService)
            .directive('badge', BadgeDirective)
            .component('ApplicationLogo', ApplicationLogo)
            .component('ModalAuthenticate', ModalAuthenticate)
            .mount(el); 
    },
    progress: {
        color: '#4B5563',
    },
});
