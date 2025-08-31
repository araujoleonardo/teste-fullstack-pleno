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

app.mount('#app');
