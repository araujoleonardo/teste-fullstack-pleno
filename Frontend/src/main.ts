import '@/style.css'
import { createApp } from 'vue'
import { createPinia } from 'pinia';
import loadingDirective from '@/directives/loading';
import VueTheMask from 'vue-the-mask';
import App from '@/App.vue'
import router from '@/router';

const app = createApp(App)

app.directive('loading', loadingDirective)
app.use(VueTheMask)
app.use(createPinia())
app.use(router)

app.mount('#app');
