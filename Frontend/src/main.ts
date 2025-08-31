import '@/style.css'
import { createApp } from 'vue'
import { createPinia } from 'pinia';
import loadingDirective from '@/directives/loading';
import VueTheMask from 'vue-the-mask';
import App from '@/App.vue'
import router from '@/router';
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { library } from '@fortawesome/fontawesome-svg-core';
import { fas } from '@fortawesome/free-solid-svg-icons';
import { fab } from '@fortawesome/free-brands-svg-icons';
import { far } from '@fortawesome/free-regular-svg-icons';

library.add(fas, fab, far);

const app = createApp(App)
const vuetify = createVuetify({
    components,
    directives,
})

app.use(vuetify)
app.directive('loading', loadingDirective)
app.use(VueTheMask)
app.use(createPinia())
app.use(router)
app.component('font-awesome-icon', FontAwesomeIcon);

app.mount('#app');
