import {createRouter, createWebHistory} from "vue-router";
import UserPage from "@/pages/UserPage.vue";
import AppLayout from "@/layouts/AppLayout.vue";
import LoginPage from "@/pages/LoginPage.vue";
import routes from "@/router/middleware.ts";


const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        {
            path: '/login',
            name: 'login',
            component: LoginPage,
        },
        {
            path: '/',
            component: AppLayout,
            children: [
                {
                    path: '/',
                    name: 'usuarios',
                    component: UserPage,
                    meta: { requiresAuth: true }
                }
            ]
        },
    ]
});

// Middleware
router.beforeEach(routes);

export default router;