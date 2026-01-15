import '../css/app.css';
import './assets/css/main.css';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import type { DefineComponent } from 'vue';
import { createApp, h } from 'vue';
import { initializeTheme } from './composables/useAppearance';
import { createPinia } from 'pinia';
import { createI18n } from 'vue-i18n';
import lang from './lang';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

const i18n = createI18n(lang);
const stores = createPinia();

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: (name) =>
        resolvePageComponent(
            `./pages/${name}.vue`,
            import.meta.glob<DefineComponent>('./pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(i18n)
            .use(stores)
            .mount(el);
    },
    progress: {
        color: '#ff2233',
    },
});

// This will set light / dark mode on page load...
initializeTheme();
