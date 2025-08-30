import type { NavigationGuardNext, RouteLocationNormalized } from 'vue-router'
import { useAuthStore } from '@/services/useAuth'

export default async function routes(
    to: RouteLocationNormalized,
    _from: RouteLocationNormalized,
    next: NavigationGuardNext
): Promise<void> {
    const auth = useAuthStore()

    try {
        const isValid = await auth.validateToken()

        //Redireciona usuários logados para dashboard se tentarem acessar rotas públicas como login/register
        if (isValid && to.meta.guestOnly) {
            return next({ name: 'dashboard' })
        }

        //Protege rotas que exigem autenticação
        if (to.meta.requiresAuth) {
            if (!isValid) {
                console.warn('Token inválido ou expirado.')
                return redirectToLogin()
            }

            const user = await auth.checkUser()

            if (user) {
                return next()
            } else {
                return redirectToLogin()
            }
        }

        // Rota pública (sem requiresAuth)
        return next()
    } catch (error) {
        console.error('Erro no middleware de autenticação:', error)
        return redirectToLogin()
    }

    function redirectToLogin() {
        auth.clear()
        next({ name: 'login' })
    }
}